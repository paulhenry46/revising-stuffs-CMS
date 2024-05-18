<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
<div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">
    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
        {{__('Legal')}}
    </h1>
</div>
<div class="bg-base-200 dark:bg-base-200 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">

Ce site est soumis au régime juridique français. À ce titre, il est un service de communication au public en ligne édité à titre non professionnel au sens de l’article 6, Ⅲ, 2° de la loi 2004‑575 du 21 juin 2004.

<h2 class="text-2xl py-2">Hébergeur</h2>

Ce site est hébergé sur les serveurs d'OVH. Siège social : 2 rue Kellermann, 59100 Roubaix - France.
<h2 class="text-2xl py-2">Directeur de publication</h2>

Le directeur de publication est {{env('INSTANCE_DIRECTOR')}}.
<h2 class="text-2xl py-2">Cookies</h2>

Seuls des cookies nécessaires au bon fonctionnement du site sont déposés, tel que des cookies de connexion.

<h2 class="text-2xl py-2">Nous contacter</h2>

Si vous avez rencontré un bogue en parcourant le site ou si vous souhaitez suggérer des évolutions pour le site, veuillez utiliser <a class="link link-primary link-hover" href="https://github.com/paulhenry46/revising-stuffs-CMS/issues">le suivi des suggestions et bogues  du site</a>
</br>
Pour toute question concernant la modération ou l’administration du site lui-même ou une problématique ne pouvant être évoquée publiquement, vous pouvez nous contacter, par courriel, à l’adresse {{str_replace('@', '[->AT<-]',env('INSTANCE_MAIL'))}}.

    </div>
</div>
</div>

            </div>
        </div>
    </div>
</x-app-layout>
