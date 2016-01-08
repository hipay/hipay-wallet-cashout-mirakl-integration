<?php
namespace Hipay\SilexIntegration\Console;

use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * File Style.php
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class Style extends SymfonyStyle
{
    /**
     * {@inheritdoc}
     */
    public function warning($message)
    {
        $this->block($message, 'WARNING', 'bg=yellow', ' ', true);
    }

    /**
     * {@inheritdoc}
     */
    public function note($message)
    {
        $this->block($message, 'NOTE', 'fg=cyan', ' ! ');
    }

}