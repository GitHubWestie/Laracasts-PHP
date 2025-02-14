<?php

$movies = [
    [
        'name' => 'Back to the Future',
        'releaseYear' => 1985,
    ],

    [
        'name' => 'Pirates of the Caribbean',
        'releaseYear' => 2003,
    ],

    [
        'name' => 'Interstellar',
        'releaseYear' => 2014,
    ],
];

function filterByRecent($movies) {
    $recentMovies = [];
    foreach($movies as $movie) {
        if($movie['releaseYear'] >= 2000) {
            $recentMovies[] = $movie;
        }
    }
    return $recentMovies;
}

$recentMovies = filterByRecent($movies);

var_dump($recentMovies);