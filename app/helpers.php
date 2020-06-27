<?php

use Carbon\Carbon;

function current_user() 
{
        return auth()->user();
}

// Calculate if tweet is more than 24 hours old
function carbonTime() 
{
        return Carbon::now()->subDay();
}