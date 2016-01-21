<?php
namespace Hipay\SilexIntegration\Console;

use Symfony\Component\Console\Helper\Helper;
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
        $this->block($message, 'NOTE', 'fg=cyan');
    }

    /**
     * {@inheritdoc}
     */
    public function subSection($message)
    {
        $this->writeln(array(
            sprintf('<comment>%s</comment>', $message),
            sprintf('<comment>%s</comment>', str_repeat('.', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
        ));
        $this->newLine();
    }
}