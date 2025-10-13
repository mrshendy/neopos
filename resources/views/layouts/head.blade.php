<!-- Title -->
<title> @yield("title") </title>
    <!-- Favicon -->
    <link rel="icon" href="{{asset('assets/images/logo.png')}}" type="image/x-icon" /> 
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{asset('assets/css/book.css')}}" rel="stylesheet"/>

    <link href="{{asset('assets/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/responsive.bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/buttons.dataTables.min.css')}}" rel="stylesheet"/>

    <!-- Layout config Js -->
    <script src="{{asset('assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet"/>
    <!-- Icons Css -->

    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet"/>
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet"/>  
    <!-- custom Css-->
    <link href="{{asset('assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />



@yield('css')
@if (session()->has('error_system'))
<script>
    window.onload = function() {
        notif({
            msg: "<b>Oops!</b> An Error Occurred",
            type: "error"
        })
    }
    
</script>
@endif
@if (session()->has('add'))
<script>
    window.onload = function() {
        notif({
            msg: "Data has been added successfully",
            type: "success"
        })
    }
   
</script>
@endif
@if (session()->has('edit_m'))
<script>
    window.onload = function() {
        notif({
            msg: "The data has been updated successfully",
            type: "success"
        })
    }
   
</script>
@endif
@if (session()->has('delete_m'))
<script>
    window.onload = function() {
        notif({
            msg: "The data has been deleted successfully",
            type: "success"
        })
    }
   
</script>
@endif
@if (session()->has('uploaded_m'))
<script>
    window.onload = function() {
        notif({
            msg: "The data has been uploaded successfully",
            type: "success"
        })
    }
   
</script>
@endif
@if (session()->has('warning_system'))
<script>
    window.onload = function() 
    {
        notif
        ({
            type: "warning",
		    msg: "<b>Warning:</b> Something Went Wrong",
        })
    }
</script>
@endif
@if (session()->has('already_exists'))
<script>
    window.onload = function() 
    {
        notif
        ({
            type: "warning",
		    msg: "<b>Warning:</b> This data already exists",
        })
    }
</script>
@endif

