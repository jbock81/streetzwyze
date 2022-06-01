(function () {
  'use strict';

  console.log('Video Preload disabler loaded');
  window.addEventListener('load', function () {
    if (document.getElementById('contentArea') === null) {
      console.log('No content found, returning');
      return;
    }

    console.log('Starting observation');
    var target = document.getElementById('contentArea');
    //create an observer instance

    window.preloadVideoObserver = new MutationObserver(function (mutations) {
      var videos = document.querySelectorAll('video[preload]');
      if (videos.length) {
        console.log('Found ' + videos.length + ' videos');
      }
      for (var i = 0; i < videos.length; i++) {
        var video = videos[i];
        //remove preloading
        video.removeAttribute('preload');
        //pause loading
        video.pause();
        //copy source
        var src = video.src;
        //remove attribute for now
        video.src = "data:video/mp4,0";

        //chill till GC is completed before restoring original source
        setTimeout(function () {
          console.log('re-inserting src');
          video.src = src;
        }, 2000);
      }
    });

    // configuration of the observer:
    var config = {childList: true, subtree: true};

    // pass in the target node, as well as the observer options
    window.preloadVideoObserver.observe(target, config);
  });
})();