<x-app-layout>
    <x-slot name="header">
    @php
     $breadcrumb = array (
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
                        <a wire:navigate href="{{route('post.public.cards.learn', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('Learn mode')}}</a>
                        <a wire:navigate href="{{route('post.public.cards.show', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('View')}}</a>
                      </div>
                    </div>
                </div>

                <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <x-info-message/>
<div class="grid grid-cols-3 gap-4">
  <div class="col-span-3">
    <div class="stack w-full h-64 sm:h-80">
    <label id="cards" data-state="defaut" class=" duration-500 data-[state=success]:bg-success dark:data-[state=success]:bg-success data-[state=fail]:bg-warning dark:data-[state=fail]:bg-warning transition ease-in-out swap grid w-full h-full rounded dark:bg-base-100 bg-white text-dark dark:text-white place-content-center">
      <input type="checkbox" />
      <div id="back" class="place-content-center align-middle flex flex-col swap-on text-center"></div>
      <div id="front" class="place-content-center align-middle flex flex-col swap-off text-center"></div>
    </label>
  <div class="grid w-full h-full rounded bg-primary text-secondary-content place-content-center"></div>
  <div class="grid w-full h-full rounded bg-success text-secondary-content place-content-center"></div>
</div>
</div>
<div class="col-span-3">
  <div class="grid grid-cols-12 gap-4 mt-2">
  <div class="col-span-2">
  <button class="btn btn-primary w-full" id="return" >
    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="48">
      <path d="M289-200q-13 0-21.5-8.5T259-230q0-13 8.5-21.5T289-260h280q70 0 120.5-46.5T740-422q0-69-50.5-115.5T569-584H274l93 93q9 9 9 21t-9 21q-9 9-21 9t-21-9L181-593q-5-5-7-10t-2-11q0-6 2-11t7-10l144-144q9-9 21-9t21 9q9 9 9 21t-9 21l-93 93h294q95 0 163.5 64T800-422q0 94-68.5 158T568-200H289Z"/>
    </svg>
  </button>
    </div>
    <div class="col-span-5">
      <button id="fail" class="btn btn-warning w-full">{{__('Fail')}}</button>
    </div>
    <div class="col-span-5">
      <button id="success" class="btn btn-success w-full">OK</button>
    </div>
    <div class="col-span-6">
      <button class="btn btn-success w-full" id="missed" type="button">{{__('Retry misses')}}</button>
    </div>
    <div class="col-span-6">
      <button class="btn btn-info w-full" id="full" type="button">{{__('Retry all')}}</button>
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
        <button class="btn">{{__('Close')}}</button>
      </form>
    </div>
  </div>
</dialog>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
//it doesn't shuffle missed anymore
/* use a filter method to show only missed cards, like
console.log(ancestry.filter(function(person) {
  return person.father == "Carel Haverbeke";
}));
// → [{name: "Carolus Haverbeke", …}]
*/


//card Array:
var startingCards = {!!$cards!!};
//copying the arrays to preserve a copy of the original

for (var i=0;i<startingCards.length; i++){
startingCards[i].status = "unread";
}

var fullCards = startingCards.slice();

var missedCards = [];

//initializing some variables
var firstTry = true;
var percent;
function sentResult(){
  @auth
  if(firstTry == true){
    var percent = Math.round((successCounter / fullCards.length) * 100);

    $.post('{{route('step.add')}}',
    {
        '_token': $('meta[name=csrf-token]').attr('content'),
        percent: percent,
        postId: '{{$post->id}}'
    });
}
@endauth
    firstTry = false;
  }


var numberCards = fullCards.length;
var indexCounter = 0;
var successCounter = 0;
var failCounter = 0;
var isMissed = false;
  //variables for the return function
var oldState = 'known';
function OldCard() {
  //document.getElementById("cards").setAttribute('data-state', 'defaulte');
  //what happens when they're going through a deck:
  if(indexCounter === 1){
    document.getElementById("return").disabled = true;
  }

  //missedCards[indexCounter].status = "missed";
  //fullCards[indexCounter].status = "known";
    indexCounter = indexCounter - 1;
    var cardCounter = indexCounter + 1;

    if(isMissed === true){
      if(missedCards[indexCounter].status === 'missed'){
        failCounter = failCounter - 1;
        document.getElementById("fail").innerHTML = "Échec (" + failCounter + ")";
      }else if(missedCards[indexCounter].status === 'known'){
        successCounter = successCounter - 1;
        document.getElementById("success").innerHTML = "OK (" + successCounter + ")";
      }
      document.getElementById("percent").value = (cardCounter / missedCards.length) * 100;
    }else{
      if(fullCards[indexCounter].status === 'missed'){
        failCounter = failCounter - 1;
        document.getElementById("fail").innerHTML = "Échec (" + failCounter + ")";
      }else if(fullCards[indexCounter].status === 'known'){
        successCounter = successCounter - 1;
        document.getElementById("success").innerHTML = "OK (" + successCounter + ")";
      }
      document.getElementById("percent").value = (cardCounter / numberCards) * 100;
    }


    /*if(oldState === 'missed'){
      failCounter = failCounter - 1;
      document.getElementById("fail").innerHTML = "Échec (" + failCounter + ")";
    }else if(oldState === 'known'){
      successCounter = successCounter - 1;
      document.getElementById("success").innerHTML = "OK (" + successCounter + ")";
    }
    var cardCounter = indexCounter + 1;
    if(isMissed === true){
      document.getElementById("percent").value = (cardCounter / missedCards.length) * 100;
    }else{
      document.getElementById("percent").value = (cardCounter / numberCards) * 100;
    }*/
    cardFront(indexCounter);
    cardBack(indexCounter);

}
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
  document.getElementById("fail").innerHTML = "{{__('Fail')}}";
  //intializing the progress bar
  var percentage = (1 / numberCards) * 100;
  document.getElementById("percent").value = percentage;
  document.getElementById("return").disabled = true;
}
setUp();
//this is the 're-set' of cards for someone who just wants to retry their missed cards
function setUpMissed() {
  document.getElementById("return").disabled = true;
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
  document.getElementById("fail").innerHTML = "{{__('Fail')}}";
  //progress bar
  
  var percentage = (1 / numberCards) * 100;
  document.getElementById("percent").value = percentage;

}
//when someone clicks either 'Missed it' or 'Got it' this is what progresses the cards forward
function nextCard() {
  //document.getElementById("cards").setAttribute('data-state', 'defaulte');
  //what happens when they're going through a deck:
  if(indexCounter == 0){
    document.getElementById("return").disabled = false;
  }
  if (indexCounter < fullCards.length - 1) {
    indexCounter = indexCounter + 1;
    cardFront(indexCounter);
    cardBack(indexCounter);

  } else if (successCounter === fullCards.length) {

    sentResult();
    //when someone reaches the end of a set and got them all right!
    document.getElementById("full").innerHTML = "{{__('Retry')}}";
    $("#full").show();
    $("#success").hide();
    $("#fail").hide();
    $("#progress").hide();
    indexCounter = 0;
    successCounter = 0;
    failCounter = 0;
    masterclass.showModal();

  } else if (failCounter === fullCards.length) {
    sentResult();
    //when someone reaches the end of the set and got them all wrong
    $("#success").hide();
    $("#fail").hide();
    $("#progress").hide();
    $("#missed").hide();
    indexCounter = 0;
    failCounter = 0;
    successCounter = 0;
    document.getElementById("full").innerHTML = "{{__('Retry')}}";
    $("#full").show();

    document.getElementById("successRate").innerHTML = "{{__('Ouch! Looks like all the cards are missed. But don\'t worry, that\'s how you learn!')}}";
    $("#successRate").show();
  } else {
    sentResult();
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
    document.getElementById("fail").innerHTML = "{{__('Fail')}}";
    document.getElementById("full").innerHTML = "{{__('Retry all')}}";
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
  if(indexCounter == 0){
    document.getElementById("return").disabled = false;
  }
  if (indexCounter < missedCards.length - 1) {

    indexCounter = indexCounter + 1;
    //displays the card back
    document.getElementById('front').innerHTML = missedCards[indexCounter].front;
    //displays the card back
    document.getElementById('back').innerHTML = missedCards[indexCounter].back;

  } else if (successCounter === missedCards.length) {
    //when someone reaches the end of a set and got them all right!

    document.getElementById("full").innerHTML = "{{__('Retry')}}";
    $("#full").show();
    $("#success").hide();
    $("#fail").hide();
    $("#progress").hide();
    indexCounter = 0;
    successCounter = 0;
    failCounter = 0;
    document.getElementById("successRate").innerHTML = "{{__('I think you know them all... just this once! Come back regularly to fix the cards in your long-term memory.')}}";

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
  oldState = "known";
  successCounter = successCounter + 1;
  document.getElementById("cards").setAttribute('data-state', 'success');
  function resetAttribute(){
  document.getElementById("cards").setAttribute('data-state', 'default');
  }
  setTimeout(resetAttribute, 300);
  //changes the button to show how many known
  document.getElementById("success").innerHTML = "OK (" + successCounter + ")";
});

//fail counter goes up by one if 'Missed it' is clicked
document.getElementById("fail").addEventListener("click", function addOneFailCounter() {
  oldState = "missed";
  failCounter = failCounter + 1;
  document.getElementById("cards").setAttribute('data-state', 'fail');
  function resetAttribute(){
  document.getElementById("cards").setAttribute('data-state', 'default');
  }
  setTimeout(resetAttribute, 300);
  //changes the button to show how many missed
  document.getElementById("fail").innerHTML = "Échec (" + failCounter + ")";
});

document.getElementById("missed").addEventListener("click", function() {
  isMissed = true;
});

document.getElementById("return").addEventListener("click", OldCard);


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

</script>

</x-app-layout>
