<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    @include('partials.head')
</head>
<body>
    <table class="body-wrap">
        <tr>
            <td></td>
            <td class="container" width="600">
                <div class="content">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="content-wrap">
                                <table  cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="content-block">
                                            <h3>Notification about Receiving Revision Decision {{ $manuscript_number }} for {{ $conference }}</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            <p>Manuscript ID: {{ $manuscript_number }}</p>
                                            <p>Manuscript Title: {{ $manuscript_title }}</p>
                                            <p>Editor: {{ $editor_title }}{{ $editor_firstname }} {{ $editor_middlename }} {{ $editor_lastname }}</p>
                                            <p>Conference: {{ $conference }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            Dear, {{ $chair_title }}{{ $chair_firstname }} {{ $chair_middlename }} {{ $chair_lastname }}, 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            You have received decision about the manuscript: {{ $manuscript_title }}.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            To read this decision, click the link provided below: 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block aligncenter">
                                            <a href="{{url('conference/chair/login')}}/{{ $number }}" class="btn-primary">Chair Login</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <div class="footer">
                        <table width="100%">
                            <tr>
                                <td class="aligncenter content-block">Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td></td>
        </tr>
    </table>
</body>
</html>