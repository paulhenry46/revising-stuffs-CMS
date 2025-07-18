<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
<div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    {{__('Licensing')}}
</h1>

</div>

<div class="bg-base-200/25 dark:bg-base-200/25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<div class="px-4 sm:px-6 lg:px-8">

<div class=" flow-root">
<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
  <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
  {{__('Licenses give everyone a standardized way to grant the public permission to use their work under copyright law. From the reuser’s perspective, the presence of a license on a copyrighted work answers the question, "What can I do with this work?".')}}
  <br>{{__('In the profil settings, each user can chose what license he want to apply to his work. A lot of licenses are proposed, including :')}}
  <h2 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">CC-BY</h2>
  {{__('This license enables reusers to distribute, remix, adapt, and build upon the material in any medium or format, so long as attribution is given to the creator.')}}
  <a class="link link-primary" href="https://creativecommons.org/licenses/by/4.0/">{{__('Terms')}}</a>
  <h2 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">CC BY-SA</h2>
    {{__('This license enables reusers to distribute, remix, adapt, and build upon the material in any medium or format, so long as attribution is given to the creator.')}} 
    {{__('If you remix, adapt, or build upon the material, you must license the modified material under identical terms.')}}
    <a class="link link-primary" href="https://creativecommons.org/licenses/by-sa/4.0/">{{__('Terms')}}</a>
    <h2 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">CC BY-NC</h2>
    {{__('This license enables reusers to distribute, remix, adapt, and build upon the material in any medium or format for noncommercial purposes only, and only so long as attribution is given to the creator.')}}
    <a class="link link-primary" href="https://creativecommons.org/licenses/by-nc/4.0/">{{__('Terms')}}</a>
    <h2 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">CC BY-NC-SA</h2>
    {{__('This license enables reusers to distribute, remix, adapt, and build upon the material in any medium or format for noncommercial purposes only, and only so long as attribution is given to the creator.')}} 
    {{__('If you remix, adapt, or build upon the material, you must license the modified material under identical terms.')}}
    <a class="link link-primary" href="https://creativecommons.org/licenses/by-nc-sa/4.0/">{{__('Terms')}}</a>
    <h2 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">CC BY-ND</h2>
    {{__('This license enables reusers to copy and distribute the material in any medium or format in unadapted form only, and only so long as attribution is given to the creator.')}}
    <a class="link link-primary" href="https://creativecommons.org/licenses/by-nd/4.0/">{{__('Terms')}}</a>
    <h2 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">CC BY-NC-ND</h2>
    {{__('This license enables reusers to copy and distribute the material in any medium or format in unadapted form only, for noncommercial purposes only, and only so long as attribution is given to the creator.')}}
    <a class="link link-primary" href="https://creativecommons.org/licenses/by-nc-nd/4.0/">{{__('Terms')}}</a>
    <h2 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">{{__('The CC0 Public Domain Dedication')}}</h2>
    {{__('CC0 (aka CC Zero) is a public dedication tool, which enables creators to give up their copyright and put their works into the worldwide public domain.')}}
    {{__('CC0 enables reusers to distribute, remix, adapt, and build upon the material in any medium or format, with no conditions.')}}
    <a class="link link-primary" href="https://creativecommons.org/publicdomain/zero/1.0/">{{__('Terms')}}</a>
    <h2 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">{{__('Need help choosing your license ?')}}</h2>
    {{__('If you don\'t know which license chose, you can user the')}} <a href="https://creativecommons.org/choose/" class="link link-primary">{{__('CC License Chooser')}}</a>.
  </div>
  {{__('From')}} <a class="link link-primary" href="https://creativecommons.org/share-your-work/cclicenses/">{{__('Creative Commons Website')}}</a>{{__(', under CC-BY License.')}}
</div>
</div>
</div>
</div>
            </div>
        </div>
    </div>
</x-app-layout>
