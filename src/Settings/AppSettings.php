<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Jbtronics\SettingsBundle\Storage\JSONFileStorageAdapter;

#[Settings(storageAdapter: JSONFileStorageAdapter::class)]
class AppSettings
{
    use SettingsTrait;

    #[SettingsParameter(
        label: 'Max number of wins',
        formOptions: [
            'attr' => [
                'min' => 0,
            ],
        ]
    )]
    public int $maxNumberOfWins = 4 * 24;

    #[SettingsParameter(
        label: 'Grand prize day',
        formOptions: [
            'attr' => [
                'min' => 1,
                'max' => 24,
            ],
        ]
    )]
    public int $grandPrizeDay = 24;
}
