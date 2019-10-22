<?php

namespace DigitalTolk\OptaplannerAdapter\Contracts\Adapters;


interface TFVServiceAdapter
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
     * get optaplanner data
     *
     * @return array
     */
    public function getData();


}
