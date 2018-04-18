 /*global jQuery */
/*!
 * jQuery Scrollbox
 * (c) 2009-2013 Hunter Wu <hunter.wu@gmail.com>
 * MIT Licensed.
 *
 * http://github.com/wmh/jquery-scrollbox
 */

(function($) {

$.fn.scrollbox = function(config) {
  //default config
  var defConfig = {
    linear: false,          // Scroll method
    startDelay: 2,          // Start delay (in seconds)
    delay: 3,               // Delay after each scroll event (in seconds)
    step: 5,                // Distance of each single step (in pixels)
    speed: 32,              // Delay after each single step (in milliseconds)
    switchItems: 1,         // Items to switch after each scroll event
    direction: 'vertical',
    distance: 'auto',
    autoPlay: true,
    onMouseOverPause: true,
    paused: false,
    queue: null,
    listElement: 'ul',
    listItemElement:'li',
    infiniteLoop: true,     // Infinite loop or not
    switchAmount: 0,        // Give a number if you don't want to have infinite loop
    afterForward: null,     // Callback function after each forward action
    afterBackward: null,    // Callback function after each backward action
    triggerStackable: false // Allow triggers when action is not finish yet
  };
  config = $.extend(defConfig, config);
  config.scrollOffset = config.direction === 'vertical' ? 'scrollTop' : 'scrollLeft';
  if (config.queue) {
    config.queue = $('#' + config.queue);
  }

  return this.each(function() {
    var container = $(this),
        containerUL,
        scrollingId = null,
        nextScrollId = null,
        paused = false,
        releaseStack,
        backward,
        forward,
        resetClock,
        scrollForward,
        scrollBackward,
        forwardHover,
        pauseHover,
        switchCount = 0,
        stackedTriggerIndex = 0;

    if (config.onMouseOverPause) {
      container.bind('mouseover', function() { paused = true; });
      container.bind('mouseout', function() { paused = false; });
    }
    containerUL = container.children(config.listElement + ':first-child');

    // init default switchAmount
    if (config.infiniteLoop === false && config.switchAmount === 0) {
      config.switchAmount = containerUL.children().length;
    }

    scrollForward = function() {
      if (paused) {
        return;
      }
      var curLi,
          i,
          newScrollOffset,
          scrollDistance,
          theStep;

      curLi = containerUL.children(config.listItemElement + ':first-child');

      scrollDistance = config.distance !== 'auto' ? config.distance :
        config.direction === 'vertical' ? curLi.outerHeight(true) : curLi.outerWidth(true);

      // offset
      if (!config.linear) {
        theStep = Math.max(3, parseInt((scrollDistance - container[0][config.scrollOffset]) * 0.3, 10));
        newScrollOffset = Math.min(container[0][config.scrollOffset] + theStep, scrollDistance);
      } else {
        newScrollOffset = Math.min(container[0][config.scrollOffset] + config.step, scrollDistance);
      }
      container[0][config.scrollOffset] = newScrollOffset;

      if (newScrollOffset >= scrollDistance) {
        for (i = 0; i < config.switchItems; i++) {
          if (config.queue && config.queue.find(config.listItemElement).length > 0) {
            containerUL.append(config.queue.find(config.listItemElement)[0]);
            containerUL.children(config.listItemElement + ':first-child').remove();
          } else {
            containerUL.append(containerUL.children(config.listItemElement + ':first-child'));
          }
          ++switchCount;
        }
        container[0][config.scrollOffset] = 0;
        clearInterval(scrollingId);
        scrollingId = null;

        if ($.isFunction(config.afterForward)) {
          config.afterForward.call(container, {
            switchCount: switchCount,
            currentFirstChild: containerUL.children(config.listItemElement + ':first-child')
          });
        }
        if (config.triggerStackable && stackedTriggerIndex !== 0) {
          releaseStack();
          return;
        }
        if (config.infiniteLoop === false && switchCount >= config.switchAmount) {
          return;
        }
        if (config.autoPlay) {
          nextScrollId = setTimeout(forward, config.delay * 1000);
        }
      }
    };

    // Backward
    // 1. If forwarding, then reverse
    // 2. If stoping, then backward once
    scrollBackward = function() {
      if (paused) {
        return;
      }
      var curLi,
          i,
          newScrollOffset,
          scrollDistance,
          theStep;

      // init
      if (container[0][config.scrollOffset] === 0) {
        for (i = 0; i < config.switchItems; i++) {
          containerUL.children(config.listItemElement + ':last-child').insertBefore(containerUL.children(config.listItemElement+':first-child'));
        }

        curLi = containerUL.children(config.listItemElement + ':first-child');
        scrollDistance = config.distance !== 'auto' ?
            config.distance :
            config.direction === 'vertical' ? curLi.height() : curLi.width();
        container[0][config.scrollOffset] = scrollDistance;
      }

      // new offset
      if (!config.linear) {
        theStep = Math.max(3, parseInt(container[0][config.scrollOffset] * 0.3, 10));
        newScrollOffset = Math.max(container[0][config.scrollOffset] - theStep, 0);
      } else {
        newScrollOffset = Math.max(container[0][config.scrollOffset] - config.step, 0);
      }
      container[0][config.scrollOffset] = newScrollOffset;

      if (newScrollOffset === 0) {
        --switchCount;
        clearInterval(scrollingId);
        scrollingId = null;

        if ($.isFunction(config.afterBackward)) {
          config.afterBackward.call(container, {
            switchCount: switchCount,
            currentFirstChild: containerUL.children(config.listItemElement + ':first-child')
          });
        }
        if (config.triggerStackable && stackedTriggerIndex !== 0) {
          releaseStack();
          return;
        }
        if (config.autoPlay) {
          nextScrollId = setTimeout(forward, config.delay * 1000);
        }
      }
    };

    releaseStack = function () {
      if (stackedTriggerIndex === 0) {
        return;
      }
      if (stackedTriggerIndex > 0) {
        stackedTriggerIndex--;
        nextScrollId = setTimeout(forward, 0);
      } else {
        stackedTriggerIndex++;
        nextScrollId = setTimeout(backward, 0);
      }
    };

    forward = function() {
      clearInterval(scrollingId);
      scrollingId = setInterval(scrollForward, config.speed);
    };

    backward = function() {
      clearInterval(scrollingId);
      scrollingId = setInterval(scrollBackward, config.speed);
    };

    // Implements mouseover function.
    forwardHover = function() {
        config.autoPlay = true;
        paused = false;
        clearInterval(scrollingId);
        scrollingId = setInterval(scrollForward, config.speed);
    };
    pauseHover = function() {
        paused = true;
    };

    resetClock = function(delay) {
      config.delay = delay || config.delay;
      clearTimeout(nextScrollId);
      if (config.autoPlay) {
        nextScrollId = setTimeout(forward, config.delay * 1000);
      }
    };

    if (config.autoPlay) {
      nextScrollId = setTimeout(forward, config.startDelay * 1000);
    }

    // bind events for container
    container.bind('resetClock', function(delay) { resetClock(delay); });
    container.bind('forward', function() {
      if (config.triggerStackable) {
        if (scrollingId !== null) {
          stackedTriggerIndex++;
        } else {
          forward();
        }
      } else {
        clearTimeout(nextScrollId);
        forward();
      }
    });
    container.bind('backward', function() {
      if (config.triggerStackable) {
        if (scrollingId !== null) {
          stackedTriggerIndex--;
        } else {
          backward();
        }
      } else {
        clearTimeout(nextScrollId);
        backward();
      }
    });
    container.bind('pauseHover', function() { pauseHover(); });
    container.bind('forwardHover', function() { forwardHover(); });
    container.bind('speedUp', function(event, speed) {
      if (speed === 'undefined') {
        speed = Math.max(1, parseInt(config.speed / 2, 10));
      }
      config.speed = speed;
    });

    container.bind('speedDown', function(event, speed) {
      if (speed === 'undefined') {
        speed = config.speed * 2;
      }
      config.speed = speed;
    });

    container.bind('updateConfig', function (event, options) {
        config = $.extend(config, options);
    });

  });
};

}(jQuery));
