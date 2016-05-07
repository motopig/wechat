<?php namespace Ecdo\Atlas;

use Illuminate\Foundation\ConfigPublisher;

class AtlasConfigPublisher extends ConfigPublisher
{
    /**
     * Get the target destination path for the configuration files.
     *
     * @param  string  $star
     * @return string
     */
    public function getDestinationPath($star)
    {
        return $this->publishPath."/atlas/{$star}";
    }
}