<?php

namespace SocialiteProviders\V2uiBbs;

use SocialiteProviders\Manager\SocialiteWasCalled;

class V2uiBbsExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('v2ui_bbs', Provider::class);
    }
}
