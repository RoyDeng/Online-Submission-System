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
                                            <h3>Welcome to Online Submission and Review System</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            Hi, {{ $name }}: 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            Your have been assigned as maintainer successfully!
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            Initial Password: {{ $password }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block aligncenter">
                                            <a href="{{url('admin/login')}}" class="btn-primary">Maintainer Login</a>
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