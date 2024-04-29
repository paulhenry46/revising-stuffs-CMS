<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                  <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                      <h1 class="text-2xl font-semibold leading-6 text-gray-900 dark:text-white">{{__('Cards attached to')}} {{$post->title}}</h1>
                    </div>
                   <div class="flex items-stretch justify-end mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                      <a href="{{route('post.public.cards.show', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('View')}}</a>
                      <a href="{{route('post.public.cards.quiz', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('Quiz Mode')}}</a>
                    </div>
                  </div>
                </div>

                <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <x-info-message/>
<div class="grid grid-cols-6 gap-4">
  <div class="col-span-6">
    <div class="stack w-full h-64 sm:h-80">
    <label class="swap grid w-full h-full rounded dark:bg-base-100 bg-white text-primary-content place-content-center text-dark dark:text-white">
      <input type="checkbox" />
      <div id="back" class="place-content-center align-middle flex flex-col swap-on text-center"></div>
      <div id="front" class="place-content-center align-middle flex flex-col swap-off text-center"></div>
    </label>
  <div class="grid w-full h-full rounded bg-primary text-secondary-content place-content-center">3</div>
  <div class="grid w-full h-full rounded bg-success text-secondary-content place-content-center">3</div>
</div>
</div>
<div class="col-span-6">
<div class="grid grid-cols-6 gap-4 mt-2">
<div class="col-span-6">
<button id="start" class="btn btn-info w-full">{{__('Start')}}</button>
</div>
<div class="col-span-6">
<button id="go_on" class="btn btn-success w-full">{{__('Go on')}}</button>
</div>
<div class="col-span-6">
<button id="reset" class="btn btn-success w-full">{{__('Restart')}}</button>
</div>
<div class="col-span-3">
<button id="fail" class="btn btn-warning w-full">{{__('Can\'t remind')}}</button>
</div>
<div class="col-span-3">
<button id="success" class="btn btn-success w-full">{{__('OK')}}</button>
</div>
</div>
</div>
<div class="col-span-3">
            <progress id="bucketA" class="progress progress-error w-full" value="50" max="100"></progress>
        <!--<div id="percent">Card 1 of 10</div>-->
</div>
<div class="col-span-3">
            <progress id="bucketB" class="progress progress-warning w-full" value="50" max="100"></progress>
</div>
<div class="col-span-6">
<progress id="bucketC" class="progress progress-success w-full" value="50" max="100"></progress>
</div>
<div class="col-span-6">
    <div id="successRate" class="mb-2">
    </div>
  </div>
                </div>

                            </div>
                        </div>
                    </div>
                     <input id="cycleLength" type="hidden" class="form-control" min="5">
<dialog id="masterclass" class="modal">
  <div class="modal-box">
    <h3 class="font-bold text-lg">{{__('Congrats')}}</h3>
    <p class="py-4">{{__('You know all the cards ! It\'s a masterclass !')}}</p>
    <div class="modal-action">
      <form method="dialog">
        <!-- if there is a button in form, it will close the modal -->
        <button class="btn">Close</button>
      </form>
    </div>
  </div>
</dialog>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>-->
<script>//Ouicards.Js
;(function(exports) {
  function loadFromArray(array) {
    ouicards.flashcards = array;
    resetBuckets();
  }

  function loadFromBrowser(selector, delimiter) {
    var flashcards = [],
        userInput  = $(selector).val().split('\n');

    // Get rid of empty fronts
    userInput = userInput.filter(function(card) {
       return card !== "";
     });

    if (userInput.length === 0) {
      console.log('There are no flashcards to upload.');
      return;
    }

    userInput.forEach(function(card) {
      var parsedCard = card.split(delimiter);
      flashcards.push({front: parsedCard[0], back: parsedCard[1]});
    });

    ouicards.flashcards = flashcards;
    resetBuckets();
    return getFromLS();
  }

  function next() {
    var newFront,
        bigInterval   = Math.ceil(ouicards.flashcards.length / 3) + 1,
        smallInterval = Math.ceil(ouicards.flashcards.length / 6) + 1;

    // Show an back from bucket C once every bigInterval
    // So long as Bucket C it's not empty
    if (ouicards.counter % bigInterval === 0 && ouicards.bucketC.length !== 0) {
      newFront = getFront(ouicards.bucketC);
      ouicards.currentBucket = ouicards.bucketC;

    // Show an back from bucket B once every smallInterval
    // So long as Bucket B it's not empty
    } else if (ouicards.counter % smallInterval === 0 && ouicards.bucketB.length !== 0) {
      newFront = getFront(ouicards.bucketB);
      ouicards.currentBucket = ouicards.bucketB;

    // Show an back from Bucket A, so long as it's not empty
    } else if (ouicards.bucketA.length !== 0) {
      newFront = getFront(ouicards.bucketA);
      ouicards.currentBucket = ouicards.bucketA;

    // Show an back from Bucket B, so long as it's not empty
    } else if (ouicards.bucketB.length !== 0) {
      newFront = getFront(ouicards.bucketB);
      ouicards.currentBucket = ouicards.bucketB;

    // Show a front from Bucket C, so long as it's not empty
    } else if (ouicards.bucketC.length !== 0) {
      newFront = getFront(ouicards.bucketC);
      ouicards.currentBucket = ouicards.bucketC;
    } else {
      console.log('There was a serious problem with ouicards. You should never see ');
    }

    // Reset ouicards.counter if it's greater than flashcard count, otherwise ++ it
    ouicards.counter >= ouicards.flashcards.length ? ouicards.counter = 1 : ouicards.counter++;
    return newFront;
  }

  function correct() {
    if (ouicards.currentBucket === ouicards.bucketA) {
      moveFront(ouicards.bucketA, ouicards.bucketB);
    } else if (ouicards.currentBucket === ouicards.bucketB) {
      moveFront(ouicards.bucketB, ouicards.bucketC);
    } else if (ouicards.currentBucket === ouicards.bucketC) {
      moveFront(ouicards.bucketC, ouicards.bucketC);
    } else
      console.log('Hmm, you should not be here.');
    saveToLS();
  }

  function wrong() {
    moveFront(ouicards.currentBucket, ouicards.bucketA);
    saveToLS();
  }

  function moveFront(fromBucket, toBucket) {
    toBucket.push(fromBucket.shift());
  }

  function getFront(bucket) {
    // Prevent from looping thru an empty bucket
    if (!bucket || bucket.length === 0) {
      console.log("You can't load an empty set of fronts.");
      return;
    }

    return buildFrontHTML(bucket[0]);
  }

  function buildFrontHTML(rawFront) {
    var frontEl, backEl;

    //  frontEl = document.createElement('p'); Deleted due to CSS issue with Images
    //  frontEl.innerHTML = rawFront.front;

    //  backEl = document.createElement('p');
    //  backEl.innerHTML = rawFront.back.replace(/\n/g, '<br>');
    frontEl = rawFront.front;
    backEl = rawFront.back.replace(/\n/g, '<br>');

    return {front: frontEl, back: backEl};
  }

  function saveToLS() {
    localStorage.{{'id'.$post['id'].''}}flashcards = JSON.stringify(ouicards.flashcards);
    localStorage.{{'id'.$post['id'].''}}bucketA    = JSON.stringify(ouicards.bucketA);
    localStorage.{{'id'.$post['id'].''}}bucketB    = JSON.stringify(ouicards.bucketB);
    localStorage.{{'id'.$post['id'].''}}bucketC    = JSON.stringify(ouicards.bucketC);
  }

  function getFromLS() {
    ouicards.flashcards    = JSON.parse(localStorage.{{'id'.$post['id'].''}}flashcards || '[]');
    ouicards.bucketA       = JSON.parse(localStorage.{{'id'.$post['id'].''}}bucketA    || '[]');
    ouicards.bucketB       = JSON.parse(localStorage.{{'id'.$post['id'].''}}bucketB    || '[]');
    ouicards.bucketC       = JSON.parse(localStorage.{{'id'.$post['id'].''}}bucketC    || '[]');
    ouicards.currentBucket = ouicards.bucketA.length ? ouicards.bucketA :
                         ouicards.bucketB.length ? ouicards.bucketB :
                         ouicards.bucketC.length ? ouicards.bucketC : [];

    ouicards.counter = 1;
    return {flashcards: ouicards.flashcards, bucketA: ouicards.bucketA, bucketB: ouicards.bucketB, bucketC: ouicards.bucketC};
  }

  function resetBuckets() {
    ouicards.bucketA       = ouicards.flashcards.slice(0);
    ouicards.currentBucket = ouicards.bucketA;
    ouicards.bucketB       = [];
    ouicards.bucketC       = [];
    ouicards.counter       = 1;
    saveToLS();
  }

  exports.ouicards = {
    currentBucket:      [],
    flashcards:         [],
    bucketA:            [],
    bucketB:            [],
    bucketC:            [],
    counter:            1,
    loadFromArray:      loadFromArray,
    loadFromBrowser:    loadFromBrowser,
    next:               next,
    correct:            correct,
    wrong:              wrong,
    moveFront:       moveFront,
    getFront:        getFront,
    buildFrontHTML:  buildFrontHTML,
    saveToLS:           saveToLS,
    getFromLS:          getFromLS,
    resetBuckets:       resetBuckets
  };

  var showNext = function() {
    var result = next();
    $('#current-front').first().html(result['front']);
    $('#current-back').first().hide().html(result['back']);
  };

  $.fn.ouicards = function() {
    var result = [];
    this.find('ul').hide().children().each(function() {
      result.push({
        front: $(this).find('.front').text(),
        back: $(this).find('.back').text()
      });
    });

    loadFromArray(result);

    $('a#correct').click(function(event) {
      event.preventDefault();
      correct();
      showNext();
    });

    $('a#wrong').click(function(event) {
      event.preventDefault();
      wrong();
      showNext();
    });

    $('a#show-back').click(function(event){
      event.preventDefault();
      $('#current-back').first().show();
    });

    showNext();
  };

})(this);
</script>
<script>
document.getElementById('cycleLength').oninput = function () {
        var min = parseInt(this.min);

        if (parseInt(this.value) < min) {
            this.value = min;
        }
    }
$('#cycleLength_valid').on('click', function() {
localStorage.{{'id'.$post['id'].''}}flashcards = '[]';
localStorage.{{'id'.$post['id'].''}}bucketA  = '[]';
localStorage.{{'id'.$post['id'].''}}bucketB = '[]';
localStorage.{{'id'.$post['id'].''}}bucketC = '[]';
localStorage.{{'id'.$post['id'].''}}cycleLength = document.getElementById("cycleLength").value;
location.reload();

});

var Flashcards = {!!$cards!!};
var start; // used to initialize the app
if(localStorage.getItem("{{'id'.$post['id'].''}}cycleLength") === null){
var cycleLength = 5;
}else{
    cycleLength = localStorage.{{'id'.$post['id'].''}}cycleLength;
}
var actualcycleLength = cycleLength;
var nextcycleLength = (2*cycleLength);
var cycleNumber = 0;
shuffledFlashcards = shuffle(Flashcards);
myFlashcards = shuffledFlashcards.slice(0, actualcycleLength);

function shuffle(arr) {
  //derived from the Fisher-Yates Shuffle
  //https://en.wikipedia.org/wiki/Fisher%E2%80%93Yates_shuffle
  for (var i = arr.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = arr[i];
    arr[i] = arr[j];
    arr[j] = temp;
  }
  return arr;
} //retu

$('#go_on').on('click', function() {
initializeHandlers();
$('#success').prop("disabled", false);
$('#fail').prop("disabled", false);
$('#go_on').hide();
$("#successRate").hide();
});

$('#reset').on('click', function() {
location.reload();
});

$(document).ready(function() {
  // Load default fronts if no flashcards are found in localStorage
  if (!localStorage.{{'id'.$post['id'].''}}flashcards || localStorage.{{'id'.$post['id'].''}}flashcards === '[]')
ouicards.loadFromArray(myFlashcards);
initializeHandlers();
});

function initializeHandlers() {
  // Unbind all events, in case the user loads new flashcard fronts
  $('#load-fronts').unbind();
  $('#success').unbind();
  $('#fail').unbind();
  $('#front').unbind();
  $('#back').unbind();
  $('#go_on').hide();
  $('#reset').hide();
  ouicards.getFromLS();
  updateFooter();

  $('#start').on('click', function() {
    if (!start) {
      console.log(start);
      start = true;
      changeFront();
      updateFooter();
      $('#start').hide();
      $('#success').prop("disabled", false);
      $('#fail').prop("disabled", false);
      return;
    }
  });

  // Correct and wrong back functionality
  $('#success').on('click', function() {
    if (!start) {
      console.log(start);
      start = true;
      changeFront();
      updateFooter()
      return;
    }
    ouicards.correct();
    if(ouicards.bucketC.length == ouicards.flashcards.length){
        if(ouicards.bucketC.length == Flashcards.length){
            $('#bucketC').text('Enfin terminé !');
            masterclass.showModal();
            localStorage.{{'id'.$post['id'].''}}flashcards = '[]';
localStorage.{{'id'.$post['id'].''}}bucketA  = '[]';
localStorage.{{'id'.$post['id'].''}}bucketB = '[]';
localStorage.{{'id'.$post['id'].''}}bucketC = '[]';
            localStorage.{{'id'.$post['id'].''}}cycleLength = cycleLength;
            $('#reset').show();
            updateFooter();
            document.getElementById("successRate").innerHTML = "<div>\r\n  <div>\r\n    <div class=\"d-flex align-items-center\">\r\n\r\n   <\/div>\r\n  <\/div>\r\n  <div class=\"card-body p-3\">\r\n    <div class=\"grid grid-cols-4 gap-4 mt-2\">\r\n      <div class=\"col-span-2 text-center\">\r\n        <div class=\"flex justify-center h-56 chart\">\r\n          <canvas id=\"myChart\" class=\" h-56 chart-canvas\"><\/canvas>\r\n        <\/div>\r\n        \r\n      <\/div>\r\n      <div class=\" col-span-2\">\r\n        <div class=\"table-responsive\">\r\n          <table class=\"table align-items-center mb-0\">\r\n            <tbody>\r\n              <tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n               \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">Connues<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-success\">" + Flashcards.length + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n<tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n                    \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">Apprises<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-info\">" + 0 + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n              <tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n                    \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">En cours<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-warning\">" + 0 + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n            <\/tbody>\r\n          <\/table>\r\n        <\/div>\r\n      <\/div>\r\n    <\/div>\r\n  <\/div>\r\n<\/div>";
    Chart.overrides.doughnut.plugins.legend.display = false;
const ctx = document.getElementById('myChart');

const data = {
  labels: [
    'Apprises',
    'Apprises lors de ce cycle',
    'À apprendre'
  ],
  datasets: [{
    label: 'Répartition',
    data: [Flashcards.length,0,0],
    backgroundColor: [
      'rgb(54, 211, 153)',
      'rgb(58, 191, 248)',
      'rgb(251, 189, 35)'
    ],
    hoverOffset: 4
  }]
};

  new Chart(ctx, {
  type: 'doughnut',
  data: data,
});
    indexCounter = 0;
    failCounter = 0;
    successCounter = 0;

    $("#successRate").show();
    $("#success").hide();
    $("#fail").hide();
        }else{
    cycleNumber++;
        updateFooter();
    ouicards.bucketA = ouicards.bucketA.concat(shuffledFlashcards.slice(actualcycleLength, nextcycleLength));
    ouicards.flashcards = ouicards.flashcards.concat(shuffledFlashcards.slice(actualcycleLength, nextcycleLength));
    localStorage.{{'id'.$post['id'].''}}flashcards = JSON.stringify(ouicards.flashcards);
    localStorage.{{'id'.$post['id'].''}}bucketA    = JSON.stringify(ouicards.bucketA);
    localStorage.{{'id'.$post['id'].''}}bucketB    = JSON.stringify(ouicards.bucketB);
    localStorage.{{'id'.$post['id'].''}}bucketC    = JSON.stringify(ouicards.bucketC);
    localStorage.{{'id'.$post['id'].''}}cycleNumber      = 'It works !';

actualcycleLength = actualcycleLength + cycleNumber*cycleLength ;
nextcycleLength = nextcycleLength + cycleNumber*cycleLength ;

  document.getElementById("successRate").innerHTML = "<div>\r\n  <div>\r\n    <div class=\"d-flex align-items-center\">\r\n\r\n   <\/div>\r\n  <\/div>\r\n  <div class=\"card-body p-3\">\r\n    <div class=\"grid grid-cols-4 gap-4 mt-2\">\r\n      <div class=\"col-span-2 text-center\">\r\n        <div class=\"flex justify-center h-56 chart\">\r\n          <canvas id=\"myChart\" class=\" h-56 chart-canvas\"><\/canvas>\r\n        <\/div>\r\n        \r\n      <\/div>\r\n      <div class=\" col-span-2\">\r\n        <div class=\"table-responsive\">\r\n          <table class=\"table align-items-center mb-0\">\r\n            <tbody>\r\n              <tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n               \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">Connues<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-success\">" + (ouicards.bucketC.length - cycleLength) + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n<tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n                    \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">Apprises<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-info\">" + cycleLength + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n              <tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n                    \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">En cours<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-warning\">" + (Flashcards.length - ouicards.bucketC.length) + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n            <\/tbody>\r\n          <\/table>\r\n        <\/div>\r\n      <\/div>\r\n    <\/div>\r\n  <\/div>\r\n<\/div>";
    Chart.overrides.doughnut.plugins.legend.display = false;
const ctx = document.getElementById('myChart');

const data = {
  labels: [
    'Apprises',
    'Apprises lors de ce cycle',
    'À apprendre'
  ],
  datasets: [{
    label: 'Répartition',
    data: [ouicards.bucketC.length - cycleLength, cycleLength, Flashcards.length - ouicards.bucketC.length],
    backgroundColor: [
      'rgb(54, 211, 153)',
      'rgb(58, 191, 248)',
      'rgb(251, 189, 35)'
    ],
    hoverOffset: 4
  }]
};

  new Chart(ctx, {
  type: 'doughnut',
  data: data,
});
    indexCounter = 0;
    failCounter = 0;
    successCounter = 0;

    $("#successRate").show();

$('#go_on').show();
$('#success').prop("disabled", true);
$('#fail').prop("disabled", true);

//initializeHandlers();
        }
}else{
    changeFront();
    updateFooter();
}
  });

  $('#fail').on('click', function() {
    if (!start) {
      start = true;
      changeFront();
      updateFooter()
      return;
    }

    ouicards.wrong();
    changeFront();
    updateFooter();
  });

  function changeFront() {
    var newFront = ouicards.next();

    if (newFront === undefined) {
      console.log('Trying to load an undefined front into the DOM.');
      return;
    }

    $('#front').html(newFront.front);
    $('#back').html(newFront.back);
    //$('.back').children().hide(); NOTE
  }



  // Update footer info
  function updateFooter() {
    $('.fronts-count').html(ouicards.flashcards.length + ' fronts');
    $('#bucketA').text(ouicards.bucketA.length);
    $('#bucketA').attr("value",(((ouicards.bucketA.length*100)/ouicards.flashcards.length)));
    $('#bucketB').text(ouicards.bucketB.length);
    $('#bucketB').attr("value",(((ouicards.bucketB.length*100)/ouicards.flashcards.length)));
    $('#bucketC').text(ouicards.bucketC.length);
    $('#bucketC').attr("value",(((ouicards.bucketC.length*100)/ouicards.flashcards.length)));

  }
}

</script>
</x-app-layout>
