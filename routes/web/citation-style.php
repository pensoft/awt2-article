<?php

Route::group(
    [
        'prefix' => 'citation-style',
    ],
    function () {
        Route::get('/{name}', 'CitationStyle\\GetCitationStyleByName')->name('web.get-citation-style');

    }
);
