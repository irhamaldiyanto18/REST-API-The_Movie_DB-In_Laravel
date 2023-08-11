<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index()
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $MAX_BANNER = 3;
        $MAX_MOVIE_ITEM = 10;
        $MAX_TV_SHOWS_ITEM = 10;

        // Hit API Banner
        $bannerResponse = Http::get("{$baseURL}/trending/movie/week", [
            'api_key' => $apiKey,
        ]);

        // Prepare Variable
        $bannerArray = [];

        // Check API Response
        if ($bannerResponse->successful()) {
            // Check data is null or not
            $resultArray = $bannerResponse->object()->results;

            if (isset($resultArray)) {
                // Looping response data
                foreach ($resultArray as $item) {
                    // Save response data to new variable
                    array_push($bannerArray, $item);

                    // Max 3 items
                    if (count($bannerArray) == $MAX_BANNER) {
                        break;
                    }
                }
            }
        }

        // Hit API Top 10 Movies
        $topMoviesResponse = Http::get("{$baseURL}/movie/top_rated", [
            'api_key' => $apiKey,
        ]);

        // Prepare Variable
        $topMoviesArray = [];

        // Check API Response
        if ($topMoviesResponse->successful()) {
            // Check data is null or not
            $resultArray = $topMoviesResponse->object()->results;

            if (isset($resultArray)) {
                // Looping response data
                foreach ($resultArray as $item) {
                    // Save response data to new variable
                    array_push($topMoviesArray, $item);

                    // Max 10 items
                    if (count($topMoviesArray) == $MAX_MOVIE_ITEM) {
                        break;
                    }
                }
            }
        }

        // Hit API Top 10 TV Shows
        $topTvShowsResponse = Http::get("{$baseURL}/tv/top_rated", [
            'api_key' => $apiKey,
        ]);

        // Prepare Variable
        $topTvShowsArray = [];

        // Check API Response
        if ($topTvShowsResponse->successful()) {
            // Check data is null or not
            $resultArray = $topTvShowsResponse->object()->results;

            if (isset($resultArray)) {
                // Looping response data
                foreach ($resultArray as $item) {
                    // Save response data to new variable
                    array_push($topTvShowsArray, $item);

                    // Max 10 items
                    if (count($topTvShowsArray) == $MAX_TV_SHOWS_ITEM) {
                        break;
                    }
                }
            }
        }

        return view('home', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'banner' => $bannerArray,
            'topMovies' => $topMoviesArray,
            'topTvShows' => $topTvShowsArray,
        ]);
    }

    public function movies()
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $sortBy = "popularity.desc";
        $page = 1;
        $minimalVoter = 100;

        // Hit API Top 10 TV Shows
        $movieResponse = Http::get("{$baseURL}/discover/movie", [
            'api_key' => $apiKey,
            'sort_by' => $sortBy,
            'vote_count.gte' => $minimalVoter,
            'page' => $page,
        ]);

        // Prepare Variable
        $movieArray = [];

        // Check API Response
        if ($movieResponse->successful()) {
            // Check data is null or not
            $resultArray = $movieResponse->object()->results;

            if (isset($resultArray)) {
                // Looping response data
                foreach ($resultArray as $item) {
                    // Save response data to new variable
                    array_push($movieArray, $item);
                }
            }
        }

        return view('movie', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'movies' => $movieArray,
            'sortBy' => $sortBy,
            'page' => $page,
            'minimalVoter' => $minimalVoter,
        ]);
    }

    public function tvShows()
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $sortBy = "popularity.desc";
        $page = 1;
        $minimalVoter = 100;

        // Hit API Top 10 TV Shows
        $tvResponse = Http::get("{$baseURL}/discover/tv", [
            'api_key' => $apiKey,
            'sort_by' => $sortBy,
            'vote_count.gte' => $minimalVoter,
            'page' => $page,
        ]);

        // Prepare Variable
        $tvArray = [];

        // Check API Response
        if ($tvResponse->successful()) {
            // Check data is null or not
            $resultArray = $tvResponse->object()->results;

            if (isset($resultArray)) {
                // Looping response data
                foreach ($resultArray as $item) {
                    // Save response data to new variable
                    array_push($tvArray, $item);
                }
            }
        }

        return view('tv', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'tvShows' => $tvArray,
            'sortBy' => $sortBy,
            'page' => $page,
            'minimalVoter' => $minimalVoter,
        ]);
    }

    public function search()
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        return view('search', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
        ]);
    }

    public function movieDetails($id)
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        $response = Http::get("{$baseURL}/movie/{$id}", [
            'api_key' => $apiKey,
            'append_to_response' => 'videos',
        ]);

        $movieData = null;

        if ($response->successful()) {
            $movieData = $response->object();
        }

        return view('movie_details', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'movieData' => $movieData,
        ]);
    }

    public function tvDetails($id)
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        $response = Http::get("{$baseURL}/tv/{$id}", [
            'api_key' => $apiKey,
            'append_to_response' => 'videos',
        ]);

        $tvData = null;

        if ($response->successful()) {
            $tvData = $response->object();
        }

        return view('tv_details', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'tvData' => $tvData,
        ]);
    }
}
