<?php

namespace src\Contracts\Adapters;


interface OptaplannerServiceAdapter
{
    /**
     * Get bookings
     *
     * @return array
     */
    public function getBookings();

    /**
     * get translators
     *
     * @return array
     */
    public function getTranslators();

    /**
     * get translator unavailable times
     *
     * @return array
     */
    public function getTranslatorUnavailableTimes();
    /**
     * get translators distance and times
     *
     * @return array
     */
    public function getDistancesTime();
    /**
     * get optaplanner data
     *
     * @return array
     */
    public function getData();

    
}
