<?php

namespace App\Support;

class UserAgentParser
{
    public static function parse($userAgent)
    {
        $browser = 'Unknown Browser';
        $platform = 'Unknown OS';
        $device = 'Desktop';

        if (empty($userAgent)) {
            return [
                'browser' => $browser,
                'platform' => $platform,
                'device_type' => 'desktop',
            ];
        }

        // Detect Platform / OS
        if (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
            $device = 'Mobile';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
            $device = preg_match('/ipad/i', $userAgent) ? 'Tablet' : 'Mobile';
        }

        // Detect Browser
        if (preg_match('/chrome/i', $userAgent) && !preg_match('/edge|edg/i', $userAgent) && !preg_match('/opr/i', $userAgent)) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) {
            $browser = 'Apple Safari';
        } elseif (preg_match('/msie|trident/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/edge|edg/i', $userAgent)) {
            $browser = 'Microsoft Edge';
        } elseif (preg_match('/opr/i', $userAgent) || preg_match('/opera/i', $userAgent)) {
            $browser = 'Opera';
        }

        // Detect device type if not already Android/iOS
        if ($device === 'Desktop') {
            if (preg_match('/mobile|phone|ipod|blackberry|opera mini|iemobile/i', $userAgent)) {
                $device = 'Mobile';
            } elseif (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
                $device = 'Tablet';
            }
        }

        return [
            'browser' => $browser,
            'platform' => $platform,
            'device_type' => strtolower($device),
        ];
    }
}
