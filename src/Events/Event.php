<?php

namespace SaliBhdr\TyphoonCache\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;
}
