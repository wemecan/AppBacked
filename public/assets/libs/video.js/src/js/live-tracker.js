import Component from './component.js';
import mergeOptions from './utils/merge-options.js';
import document from 'global/document';
import * as browser from './utils/browser.js';
import window from 'global/window';
import * as Fn from './utils/fn.js';

const defaults = {
  trackingThreshold: 30,
  liveTolerance: 15
};

/*
  track when we are at the live edge, and other helpers for live playback */

/**
 * A class for checking live current time and determining when the player
 * is at or behind the live edge.
 */
class LiveTracker extends Component {

  /**
   * Creates an instance of this class.
   *
   * @param {Player} player
   *        The `Player` that this class should be attached to.
   *
   * @param {Object} [options]
   *        The key/value store of player options.
   *
   * @param {number} [options.trackingThreshold=30]
   *        Number of seconds of live window (seekableEnd - seekableStart) that
   *        media needs to have before the liveui will be shown.
   *
   * @param {number} [options.liveTolerance=15]
   *        Number of seconds behind live that we have to be
   *        before we will be considered non-live. Note that this will only
   *        be used when playing at the live edge. This allows large seekable end
   *        changes to not effect wether we are live or not.
   */
  constructor(player, options) {
    // LiveTracker does not need an element
    const options_ = mergeOptions(defaults, options, {createEl: false});

    super(player, options_);

    this.reset_();

    this.on(this.player_, 'durationchange', this.handleDurationchange);

    // we don't need to track live playback if the document is hidden,
    // also, tracking when the document is hidden can
    // cause the CPU to spike and eventually crash the page on IE11.
    if (browser.IE_VERSION && 'hidden' in document && 'visibilityState' in document) {
      this.on(document, 'visibilitychange', this.handleVisibilityChange);
    }
  }

  /**
   * toggle tracking based on document visiblility
   */
  handleVisibilityChange() {
    if (this.player_.duration() !== Infinity) {
      return;
    }

    if (document.hidden) {
      this.stopTracking();
    } else {
      this.startTracking();
    }
  }

  /**
   * all the functionality for tracking when seek end changes
   * and for tracking how far past seek end we should be
   */
  trackLive_() {
    const seekable = this.player_.seekable();

    // skip undefined seekable
    if (!seekable || !seekable.length) {
      return;
    }

    const newTime = Number(window.performance.now().toFixed(4));
    const deltaTime = this.lastTime_ === -1 ? 0 : (newTime - this.lastTime_) / 1000;

    this.lastTime_ = newTime;

    this.pastSeekEnd_ = this.pastSeekEnd() + deltaTime;

    const liveCurrentTime = this.liveCurrentTime();
    const currentTime = this.player_.currentTime();

    // we are behind live if any are true
    // 1. the player is paused
    // 2. the user seeked to a location 2 seconds away from live
    // 3. the difference between live and current time is greater
    //    liveTolerance which defaults to 15s
    let isBehind = this.player_.paused() || this.seekedBehindLive_ ||
      Math.abs(liveCurrentTime - currentTime) > this.options_.liveTolerance;

    // we cannot be behind if
    // 1. until we have not seen a timeupdate yet
    // 2. liveCurrentTime is Infinity, which happens on Android
    if (!this.timeupdateSeen_ || liveCurrentTime === Infinity) {
      isBehind = false;
    }

    if (isBehind !== this.behindLiveEdge_) {
      this.behindLiveEdge_ = isBehind;
      this.trigger('liveedgechange');
    }
  }

  /**
   * handle a durationchange event on the player
   * and start/stop tracking accordingly.
   */
  handleDurationchange() {
    if (this.player_.duration() === Infinity && this.liveWindow() >= this.options_.trackingThreshold) {
      if (this.player_.options_.liveui) {
        this.player_.addClass('vjs-liveui');
      }
      this.startTracking();
    } else {
      this.player_.removeClass('vjs-liveui');
      this.stopTracking();
    }
  }

  /**
   * start tracking live playback
   */
  startTracking() {
    if (this.isTracking()) {
      return;
    }

    // If we haven't seen a timeupdate, we need to check whether playback
    // began before this component started tracking. This can happen commonly
    // when using autoplay.
    if (!this.timeupdateSeen_) {
      this.timeupdateSeen_ = this.player_.hasStarted();
    }

    this.trackingInterval_ = this.setInterval(this.trackLive_, Fn.UPDATE_REFRESH_INTERVAL);
    this.trackLive_();

    this.on(this.player_, ['play', 'pause'], this.trackLive_);

    if (!this.timeupdateSeen_) {
      this.one(this.player_, 'play', this.handlePlay);
      this.one(this.player_, 'timeupdate', this.handleFirstTimeupdate);
    } else {
      this.on(this.player_, 'seeked', this.handleSeeked);
    }
  }

  /**
   * handle the first timeupdate on the player if it wasn't already playing
   * when live tracker started tracking.
   */
  handleFirstTimeupdate() {
    this.timeupdateSeen_ = true;
    this.on(this.player_, 'seeked', this.handleSeeked);
  }

  /**
   * Keep track of what time a seek starts, and listen for seeked
   * to find where a seek ends.
   */
  handleSeeked() {
    const timeDiff = Math.abs(this.liveCurrentTime() - this.player_.currentTime());

    this.seekedBehindLive_ = this.skipNextSeeked_ ? false : timeDiff > 2;
    this.skipNextSeeked_ = false;
    this.trackLive_();
  }

  /**
   * handle the first play on the player, and make sure that we seek
   * right to the live edge.
   */
  handlePlay() {
    this.one(this.player_, 'timeupdate', this.seekToLiveEdge);
  }

  /**
   * Stop tracking, and set all internal variables to
   * their initial value.
   */
  reset_() {
    this.lastTime_ = -1;
    this.pastSeekEnd_ = 0;
    this.lastSeekEnd_ = -1;
    this.behindLiveEdge_ = true;
    this.timeupdateSeen_ = false;
    this.seekedBehindLive_ = false;
    this.skipNextSeeked_ = false;

    this.clearInterval(this.trackingInterval_);
    this.trackingInterval_ = null;

    this.off(this.player_, ['play', 'pause'], this.trackLive_);
    this.off(this.player_, 'seeked', this.handleSeeked);
    this.off(this.player_, 'play', this.handlePlay);
    this.off(this.player_, 'timeupdate', this.handleFirstTimeupdate);
    this.off(this.player_, 'timeupdate', this.seekToLiveEdge);
  }

  /**
   * stop tracking live playback
   */
  stopTracking() {
    if (!this.isTracking()) {
      return;
    }
    this.reset_();
    this.trigger('liveedgechange');
  }

  /**
   * A helper to get the player seekable end
   * so that we don't have to null check everywhere
   *
   * @return {number}
   *         The furthest seekable end or Infinity.
   */
  seekableEnd() {
    const seekable = this.player_.seekable();
    const seekableEnds = [];
    let i = seekable ? seekable.length : 0;

    while (i--) {
      seekableEnds.push(seekable.end(i));
    }

    // grab the furthest seekable end after sorting, or if there are none
    // default to Infinity
    return seekableEnds.length ? seekableEnds.sort()[seekableEnds.length - 1] : Infinity;
  }

  /**
   * A helper to get the player seekable start
   * so that we don't have to null check everywhere
   *
   * @return {number}
   *         The earliest seekable start or 0.
   */
  seekableStart() {
    const seekable = this.player_.seekable();
    const seekableStarts = [];
    let i = seekable ? seekable.length : 0;

    while (i--) {
      seekableStarts.push(seekable.start(i));
    }

    // grab the first seekable start after sorting, or if there are none
    // default to 0
    return seekableStarts.length ? seekableStarts.sort()[0] : 0;
  }

  /**
   * Get the live time window aka
   * the amount of time between seekable start and
   * live current time.
   *
   * @return {number}
   *         The amount of seconds that are seekable in
   *         the live video.
   */
  liveWindow() {
    const liveCurrentTime = this.liveCurrentTime();

    if (liveCurrentTime === Infinity) {
      return Infinity;
    }

    return liveCurrentTime - this.seekableStart();
  }

  /**
   * Determines if the player is live, only checks if this component
   * is tracking live playback or not
   *
   * @return {boolean}
   *         Wether liveTracker is tracking
   */
  isLive() {
    return this.isTracking();
  }

  /**
   * Determines if currentTime is at the live edge and won't fall behind
   * on each seekableendchange
   *
   * @return {boolean}
   *         Wether playback is at the live edge
   */
  atLiveEdge() {
    return !this.behindLiveEdge();
  }

  /**
   * get what we expect the live current time to be
   *
   * @return {number}
   *         The expected live current time
   */
  liveCurrentTime() {
    return this.pastSeekEnd() + this.seekableEnd();
  }

  /**
   * The number of seconds that have occured after seekable end
   * changed. This will be reset to 0 once seekable end changes.
   *
   * @return {number}
   *         Seconds past the current seekable end
   */
  pastSeekEnd() {
    const seekableEnd = this.seekableEnd();

    if (this.lastSeekEnd_ !== -1 && seekableEnd !== this.lastSeekEnd_) {
      this.pastSeekEnd_ = 0;
    }
    this.lastSeekEnd_ = seekableEnd;
    return this.pastSeekEnd_;
  }

  /**
   * If we are currently behind the live edge, aka currentTime will be
   * behind on a seekableendchange
   *
   * @return {boolean}
   *         If we are behind the live edge
   */
  behindLiveEdge() {
    return this.behindLiveEdge_;
  }

  /**
   * Wether live tracker is currently tracking or not.
   */
  isTracking() {
    return typeof this.trackingInterval_ === 'number';
  }

  /**
   * Seek to the live edge if we are behind the live edge
   */
  seekToLiveEdge() {
    this.seekedBehindLive_ = false;
    if (this.atLiveEdge()) {
      return;
    }
    // skipNextSeeked_
    this.skipNextSeeked_ = true;
    this.player_.currentTime(this.liveCurrentTime());

  }

  /**
   * Dispose of liveTracker
   */
  dispose() {
    this.off(document, 'visibilitychange', this.handleVisibilityChange);
    this.stopTracking();
    super.dispose();
  }
}

Component.registerComponent('LiveTracker', LiveTracker);
export default LiveTracker;
