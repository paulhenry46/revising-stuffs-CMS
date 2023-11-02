<x-app-layout>
    <x-slot name="header">
    @php
     $breadcrumb = array (
  array(__('Posts'),'post.public.library'),
  array($post->title,NULL),
  array(__('Cards'),NULL)
        );
      @endphp
   
     <x-breadcrumb :items=$breadcrumb/>   
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <div class="sm:flex sm:items-center">
                      <div class="sm:flex-auto">
                        <h1 class="text-2xl font-semibold leading-6 text-gray-900 dark:text-white">{{__('Cards attached to')}} {{$post->title}}</h1>
                      </div>
                     <div class="flex items-stretch justify-end mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <a href="{{route('post.public.cards.learn', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('Learn mode')}}</a>
                        <a href="{{route('post.public.cards.show', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('View')}}</a>
                      </div>
                    </div>
                </div>

                <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <x-info-message/>
<div class="grid grid-cols-3 gap-4">
  <div class="col-span-3">
    <div class="stack w-full h-64 sm:h-80">
    <label id="cards" data-state="defaut" class=" duration-500 data-[state=success]:bg-success dark:data-[state=success]:bg-success-content data-[state=fail]:bg-warning dark:data-[state=fail]:bg-warning-content transition ease-in-out swap grid w-full h-full rounded dark:bg-base-100 bg-white text-primary-content place-content-center">
      <input type="checkbox" />
      <div id="back" class="swap-on text-center"></div>
      <div id="front" class="swap-off text-center"></div>
    </label>
  <div class="grid w-full h-full rounded bg-primary text-secondary-content place-content-center"></div>
  <div class="grid w-full h-full rounded bg-success text-secondary-content place-content-center"></div>
</div>
</div>
<div class="col-span-3">
<div class="grid grid-cols-2 gap-4 mt-2">
<div class="col-span-1">
<button id="fail" class="btn btn-warning w-full">Échec</button>
</div>
<div class="col-span-1">
<button id="success" class="btn btn-success w-full">OK</button>
</div>
<div class="col-span-1">
<button class="btn btn-success w-full" id="missed" type="button">Réessayer les manquées</button>
</div>
<div class="col-span-1">
<button class="btn btn-info w-full" id="full" type="button">Tout réessayer</button>
</div>
</div>
</div>
<div class="col-span-3">
<div id="progress">
  <div class="">
              
            <progress id="percent" class="progress progress-success w-full" value="100" max="100"></progress>
        <!--<div id="percent">Card 1 of 10</div>-->
                                <div id="bar"></div>
                              </div>
                              
                            </div>
                            <div id="successRate" class="mb-2">
      </div>
</div>
                </div>

                            </div>
                        </div>
                    </div>
<dialog id="masterclass" class="modal">
  <div class="modal-box">
    <h3 class="font-bold text-lg">{{__('Congrats')}}</h3>
    <p class="py-4">{{__('You know all the cards !')}}</p>
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
<script>
  //TODO faire mode où l'on écrit du texte et on valide pour savoir si c'est juste : avec getElementById("success").addEventListener

//it doesn't shuffle missed anymore
/* use a filter method to show only missed cards, like
console.log(ancestry.filter(function(person) {
  return person.father == "Carel Haverbeke";
}));
// → [{name: "Carolus Haverbeke", …}]
*/
//tabs
$('ul.tabs').each(function() {
  // for each set of tabs keep track of active/not
  var $active, $content, $links = $(this).find('a');

  // default to open on first tab
  $active = $($links.filter('[href="' + location.hash + '"]')[0] || $links[0]);
  $active.addClass('active');

  $content = $($active[0].hash);

  // hide everything else
  $links.not($active).each(function() {
    $(this.hash).hide();
  });

  $(this).on('click', 'a', function(e) {
    // make the old tab inactive
    $active.removeClass('active');
    $content.hide();

    $active = $(this);
    $content = $(this.hash);

    // make tab active
    $active.addClass('active');
    $content.show();

    e.preventDefault();
  });
});

$(document).on("click", 'a', function() {
  $('a').removeClass('active');
  $(this).addClass('active');
});
//end tabs

//flip the card when it's clicked:


//card Array:
var startingCards = {!!$cards!!};
//copying the arrays to preserve a copy of the original

for (var i=0;i<startingCards.length; i++){
startingCards[i].status = "unread";
}

var fullCards = startingCards.slice();

var missedCards = [];

//initializing some variables
var numberCards = fullCards.length;
var indexCounter = 0;
var successCounter = 0;
var failCounter = 0;
var isMissed = false;

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
} //returns a randomly re-ordered array

//displays the card front
function cardFront(number) {
  document.getElementById('front').innerHTML = fullCards[number].front;
}
//displays the card back
function cardBack(number) {
  document.getElementById('back').innerHTML = fullCards[number].back;
}

function setUp() {
  fullCards = startingCards.slice();
  successCounter = 0;
  failCounter = 0;
  indexCounter = 0;
  numberCards = fullCards.length;
  shuffle(fullCards);
  cardFront(indexCounter);
  cardBack(indexCounter);
  $("#full").hide();
  $("#missed").hide();
  document.getElementById("success").innerHTML = "OK";
  document.getElementById("fail").innerHTML = "Échec";
  //intializing the progress bar
  var percentage = (1 / numberCards) * 100;
  document.getElementById("percent").value = percentage;
}
setUp();
//this is the 're-set' of cards for someone who just wants to retry their missed cards
function setUpMissed() {
  if (missedCards.length > 0) {
    for (var j = 0; j < missedCards.length; j++) {
      if (missedCards[j].status === "known") {
        for (var k = 0; k < fullCards.length; k++) {
          if (missedCards[j].front === fullCards[k].front && missedCards[j].back === fullCards[k].back) {
            fullCards[k].status = "known";
          }
        }
      }
    }
  }

  //clear the missedcard array
  missedCards.length = 0;
  for (var i = 0; i < fullCards.length; i++) {
    if (fullCards[i].status === "missed") {
      missedCards.push({
        "front": fullCards[i].front,
        "back": fullCards[i].back,
        "status": fullCards[i].status
      });
    }
  }

  successCounter = 0;
  failCounter = 0;
  indexCounter = 0;
  $("#success").show();
  $("#fail").show();
  $("#progress").show();
  $("#successRate").hide();
  $("#full").hide();
  $("#missed").hide();

  numberCards = missedCards.length;

  shuffle(missedCards);

  document.getElementById("front").innerHTML = missedCards[indexCounter].front;

  document.getElementById("back").innerHTML = missedCards[indexCounter].back;

  document.getElementById("success").innerHTML = "OK";
  document.getElementById("fail").innerHTML = "Échec";
  //progress bar
  
  var percentage = (1 / numberCards) * 100;
  document.getElementById("percent").value = percentage;

}
//when someone clicks either 'Missed it' or 'Got it' this is what progresses the cards forward
function nextCard() {
  //document.getElementById("cards").setAttribute('data-state', 'defaulte');
  //what happens when they're going through a deck:
  if (indexCounter < fullCards.length - 1) {
    indexCounter = indexCounter + 1;
    cardFront(indexCounter);
    cardBack(indexCounter);

  } else if (successCounter === fullCards.length) {

    //when someone reaches the end of a set and got them all right!
    document.getElementById("full").innerHTML = "Recommencer";
    $("#full").show();
    $("#success").hide();
    $("#fail").hide();
    $("#progress").hide();
    indexCounter = 0;
    successCounter = 0;
    failCounter = 0;
    masterclass.showModal();

  } else if (failCounter === fullCards.length) {
    //when someone reaches the end of the set and got them all wrong
    $("#success").hide();
    $("#fail").hide();
    $("#progress").hide();
    $("#missed").hide();
    indexCounter = 0;
    failCounter = 0;
    successCounter = 0;
    document.getElementById("full").innerHTML = "Recommencer";
    $("#full").show();

    document.getElementById("successRate").innerHTML = "Aie ! On dirait que toutes les cartes sont manquées. Mais pas d'inquiétude, c'est comme ça qu'on apprend !";
    $("#successRate").show();
  } else {
    //when someone reaches the end of a set having missed some
    document.getElementById("successRate").innerHTML = "<div>\r\n  <div>\r\n    <div class=\"d-flex align-items-center\">\r\n\r\n   <\/div>\r\n  <\/div>\r\n  <div class=\"card-body p-3\">\r\n    <div class=\"grid grid-cols-4 gap-4 mt-2\">\r\n      <div class=\"col-span-2 text-center\">\r\n        <div class=\"flex justify-center h-56 chart\">\r\n          <canvas id=\"myChart\" class=\" h-56 chart-canvas\"><\/canvas>\r\n        <\/div>\r\n        \r\n      <\/div>\r\n      <div class=\" col-span-2\">\r\n        <div class=\"table-responsive\">\r\n          <table class=\"table align-items-center mb-0\">\r\n            <tbody>\r\n              <tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n               \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">Connues<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-success\">" + (fullCards.length - failCounter - successCounter) + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n<tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n                    \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">Apprises<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-info\">" + successCounter + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n              <tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n                    \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">En cours<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-warning\">" + failCounter + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n            <\/tbody>\r\n          <\/table>\r\n        <\/div>\r\n      <\/div>\r\n    <\/div>\r\n  <\/div>\r\n<\/div>";

    $("#successRate").show();
    shuffle(fullCards);
Chart.overrides.doughnut.plugins.legend.display = false;
const ctx = document.getElementById('myChart');
const data = {
  labels: [
    'Connues',
    'Apprises lors de ce cycle',
    'En cours'
  ],
  datasets: [{
    label: 'Répartition',
    data: [fullCards.length - failCounter - successCounter, successCounter, failCounter],//data: [successCounter, failCounter],
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
    successCounter = 0;
    failCounter = 0;
    document.getElementById("success").innerHTML = "OK";
    document.getElementById("fail").innerHTML = "Échec";
    document.getElementById("full").innerHTML = "Tout réessayer";
    $("#full").show();
    $("#missed").show();
    $("#success").hide();
    $("#fail").hide();
    $("#progress").hide();

  }
  //happens each time a person progresses through the cards
  var cardCounter = indexCounter + 1;
  document.getElementById("percent").value = (cardCounter / numberCards) * 100;
}

function nextCardMissed() {
  //what happens when they're going through a deck:
  if (indexCounter < missedCards.length - 1) {

    indexCounter = indexCounter + 1;
    //displays the card back
    document.getElementById('front').innerHTML = missedCards[indexCounter].front;
    //displays the card back
    document.getElementById('back').innerHTML = missedCards[indexCounter].back;

  } else if (successCounter === missedCards.length) {
    //when someone reaches the end of a set and got them all right!

    document.getElementById("full").innerHTML = "Recommencer";
    $("#full").show();
    $("#success").hide();
    $("#fail").hide();
    $("#progress").hide();
    indexCounter = 0;
    successCounter = 0;
    failCounter = 0;
    document.getElementById("successRate").innerHTML = "Bravo ! <br> J'ai l'impression que vous les conaissez toutes... pour cette fois-ci ! Revenez régulièrement pour fixer les cartes dansla mémoire à long terme.";

    $("#successRate").show();
  } else if (failCounter === missedCards.length) {
    //when someone reaches the end of the set and got them all wrong
    $("#missed").show();
    $("#full").show();
    $("#success").hide();
    $("#fail").hide();
    $("#progress").hide();
    document.getElementById("successRate").innerHTML = "<div>\r\n  <div>\r\n    <div class=\"d-flex align-items-center\">\r\n\r\n   <\/div>\r\n  <\/div>\r\n  <div class=\"card-body p-3\">\r\n    <div class=\"grid grid-cols-4 gap-4 mt-2\">\r\n      <div class=\"col-span-2 text-center\">\r\n        <div class=\"flex justify-center h-56 chart\">\r\n          <canvas id=\"myChart\" class=\" h-56 chart-canvas\"><\/canvas>\r\n        <\/div>\r\n        \r\n      <\/div>\r\n      <div class=\" col-span-2\">\r\n        <div class=\"table-responsive\">\r\n          <table class=\"table align-items-center mb-0\">\r\n            <tbody>\r\n              <tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n               \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">Connues<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-success\">" + (fullCards.length - failCounter - successCounter) + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n<tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n                    \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">Apprises<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-info\">" + successCounter + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n              <tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n                    \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">En cours<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-warning\">" + failCounter + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n            <\/tbody>\r\n          <\/table>\r\n        <\/div>\r\n      <\/div>\r\n    <\/div>\r\n  <\/div>\r\n<\/div>";
    Chart.overrides.doughnut.plugins.legend.display = false;
const ctx = document.getElementById('myChart');
const data = {
  labels: [
    'Connues',
    'Apprises lors de ce cycle',
    'En cours'
  ],
  datasets: [{
    label: 'Répartition',
    data: [fullCards.length - failCounter - successCounter, successCounter, failCounter],//data: [successCounter, failCounter],
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
  } else {
    //when someone reaches the end of a set having missed some
    document.getElementById("successRate").innerHTML = "<div>\r\n  <div>\r\n    <div class=\"d-flex align-items-center\">\r\n\r\n   <\/div>\r\n  <\/div>\r\n  <div class=\"card-body p-3\">\r\n    <div class=\"grid grid-cols-4 gap-4 mt-2\">\r\n      <div class=\"col-span-2 text-center\">\r\n        <div class=\"flex justify-center h-56 chart\">\r\n          <canvas id=\"myChart\" class=\" h-56 chart-canvas\"><\/canvas>\r\n        <\/div>\r\n        \r\n      <\/div>\r\n      <div class=\" col-span-2\">\r\n        <div class=\"table-responsive\">\r\n          <table class=\"table align-items-center mb-0\">\r\n            <tbody>\r\n              <tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n               \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">Connues<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-success\">" + (fullCards.length - failCounter - successCounter) + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n<tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n                    \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">Apprises<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-info\">" + successCounter + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n              <tr>\r\n                <td>\r\n                  <div class=\"d-flex px-2 py-0\">\r\n                    \r\n                    <div class=\"d-flex flex-column justify-content-center\">\r\n                      <h6 class=\"mb-0 text-sm\">En cours<\/h6>\r\n                    <\/div>\r\n                  <\/div>\r\n                <\/td>\r\n                <td class=\"align-middle text-center text-sm\">\r\n                  <span class=\"badge badge-warning\">" + failCounter + "</span>\r\n                <\/td>\r\n              <\/tr>\r\n            <\/tbody>\r\n          <\/table>\r\n        <\/div>\r\n      <\/div>\r\n    <\/div>\r\n  <\/div>\r\n<\/div>";
    Chart.overrides.doughnut.plugins.legend.display = false;
const ctx = document.getElementById('myChart');
const data = {
  labels: [
    'Connues',
    'Apprises',
    'En cours'
  ],
  datasets: [{
    label: 'Répartition',
    data: [fullCards.length - failCounter - successCounter, successCounter, failCounter],//data: [successCounter, failCounter],
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
    $("#successRate").show();
    indexCounter = 0;
    successCounter = 0;
    failCounter = 0;
    document.getElementById("success").innerHTML = "OK";
    document.getElementById("fail").innerHTML = "Échec";
    document.getElementById("full").innerHTML = "Tout réessayer";
    $("#full").show();
    $("#missed").show();
    $("#success").hide();
    $("#fail").hide();
    $("#progress").hide();

  }
  //happens each time a person progresses through the cards
  var cardCounter = indexCounter + 1;
  document.getElementById("percent").value = (cardCounter / missedCards.length) * 100;
}

//a success counter goes up by 1 each time someone presses the 'got it' button
document.getElementById("success").addEventListener("click", function addOneSuccessCounter() {
  successCounter = successCounter + 1;
  document.getElementById("cards").setAttribute('data-state', 'success');
  function resetAttribute()
{
  document.getElementById("cards").setAttribute('data-state', 'default');
}

  setTimeout(resetAttribute, 300);
  //changes the button to show how many known
  document.getElementById("success").innerHTML = "OK (" + successCounter + ")";

});

//fail counter goes up by one if 'Missed it' is clicked
document.getElementById("fail").addEventListener("click", function addOneFailCounter() {
  failCounter = failCounter + 1;
  document.getElementById("cards").setAttribute('data-state', 'fail');
  function resetAttribute()
{
  document.getElementById("cards").setAttribute('data-state', 'default');
}

  setTimeout(resetAttribute, 300);

  //changes the button to show how many missed
  document.getElementById("fail").innerHTML = "Échec (" + failCounter + ")";
});

document.getElementById("missed").addEventListener("click", function() {
  isMissed = true;
});
document.getElementById("full").addEventListener("click", function() {
  isMissed = false;
});
//marks card as known
function markIfKnown() {
  if (isMissed) {
    missedCards[indexCounter].status = "known";
  } else {
    fullCards[indexCounter].status = "known";
  }

}
document.getElementById("full").addEventListener("click", function() {
  $("#success").show();
  $("#fail").show();
  $("#progress").show();
  $("#successRate").hide();
});
document.getElementById("missed").addEventListener("click", function() {
  $("#success").show();
  $("#fail").show();
  $("#progress").show();
  $("#successRate").hide();
});
document.getElementById("success").addEventListener("click", markIfKnown);

function markIfMissed() {
  if (isMissed) {
    missedCards[indexCounter].status = "missed";
  } else {
    fullCards[indexCounter].status = "missed";
  }

}
document.getElementById("fail").addEventListener("click", markIfMissed);
document.getElementById("full").addEventListener("click", setUp);
document.getElementById("missed").addEventListener("click", setUpMissed);

function whichCardSet() {
  if (isMissed) {
    nextCardMissed();
  } else {
    nextCard();
  }
}
document.getElementById("fail").addEventListener("click", whichCardSet);
document.getElementById("success").addEventListener("click", whichCardSet);
//end of 'Quiz Mode'
//'Study Mode' - show all the cards in order
function showAll(arr) {
  for (var i = 0; i < arr.length; i++) {
    var frontContent = arr[i].front;
    var backContent = arr[i].back;
    $("#studyCards").append("<div class =\"card front study\">" + frontContent + "</div>")
    $("#studyCards").append("<div class = \"card back study\">" + backContent + "</div><br>")
  }
}
showAll(startingCards);
</script>

</x-app-layout>
