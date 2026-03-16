<?php

return [

    /*
    |--------------------------------------------------------------------------
    | LaTeX / PDF Pack Generation
    |--------------------------------------------------------------------------
    |
    | Set LATEX_ENABLED=true in your .env file to enable the post-pack PDF
    | generation feature. This requires pdflatex (e.g. texlive-full) to be
    | installed on the server.
    |
    */

    'latex_enabled' => env('LATEX_ENABLED', false),

];
