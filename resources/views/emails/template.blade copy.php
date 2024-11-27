<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
    <style>table {border: none;}</style>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">
    <div style="width: 100%; max-width: 600px;">
    @php
        $facebook = getConfigObject('facebook');
        $twitter = getConfigObject('twitter');
        $linkedin = getConfigObject('linkedin');
        $instagram = getConfigObject('instagram');
        $logo = getLogo();
    @endphp
    <!-- Header Section -->
    <table width="100%" cellpadding="0" cellspacing="0"  style="border: none;" border="0">
        <tr>
            <td style="background: linear-gradient(90deg, rgba(123,204,47,1) 0%, rgba(21,160,139,1) 90%); padding: 20px;">
                <table width="600" cellpadding="0" cellspacing="0" border="0" align="center">
                    <tr>
                        <td style="width: 100%; text-align: center;">
                            <a href="{{url('/')}}"><img src="{{$logo}}" alt="Logo" style="max-width: 150px;"></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Body Section -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border: none; background-color: #ffffff;">
        <tr>
            <td style="padding: 40px 20px;">
                <table width="600" cellpadding="0" cellspacing="0" border="0" align="center">
                    <tr>
                        <td style="text-align: left; color: #333333;">
                            {body_content}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer Section -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border: none;">
        <tr>
            <td style="background: linear-gradient(90deg, rgba(123,204,47,1) 0%, rgba(21,160,139,1) 90%); padding: 20px;">
                <table width="600" cellpadding="0" cellspacing="0" border="0" align="center">
                    <tr>
                        
                        <td style="text-align: center;">
                            <!-- Social Media Icons (Replace with your own icons and links) -->
                            @if($facebook->value)
                                <a href="{{$facebook->value}}" style="margin: 0 10px; text-decoration: none;">
                                    <img src="{{ asset('assets/img/icons/facebook_round.png') }}" alt="Facebook" style="width: 24px; height: 24px;">
                                </a>
                            @endif
                            @if($instagram->value)
                                <a href="{{$instagram->value}}" style="margin: 0 10px; text-decoration: none;">
                                    <img src="{{ asset('assets/img/icons/instagram_round.png') }}" alt="Instagram" style="width: 24px; height: 24px;">
                                </a>
                            @endif
                            @if($twitter->value)
                                <a href="{{$twitter->value}}" style="margin: 0 10px; text-decoration: none;">
                                    <img src="{{ asset('assets/img/icons/twitter_round.png') }}" alt="Twitter" style="width: 24px; height: 24px;">
                                </a>
                            @endif
                            @if($linkedin->value)
                                <a href="{{$linkedin->value}}" style="margin: 0 10px; text-decoration: none;">
                                    <img src="{{ asset('assets/img/icons/linkedin_round.png') }}" alt="Linked In" style="width: 24px; height: 24px;">
                                </a>
                            @endif
                            
                            <!-- Add more icons as needed -->
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; color: #ffffff; font-size: 14px; padding-top: 20px;">
                            © 2024 Human-I-T. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
