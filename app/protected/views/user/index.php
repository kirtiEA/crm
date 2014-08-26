
<script type="text/javascript">
    $(function() {
        $('.mon_menu').each(function() {
            $(this).removeClass('active');
        });
        $('.menu_user').addClass('active');
    });
</script>

<!-- add new user subheader -->    

<div class="container-fluid sub-header">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontol" role="form">
                <div class="form-group">
                    <h3 class="subheader-heading">Add New User</h3>
                    <div class="control">
                        <label class="control-label">Username</label>
                        <input type="text">
                    </div>
                    <div class="control">
                        <label class="control-label">Password</label>
                        <input type="password">
                    </div>
                    <div class="control">
                        <label class="control-label">Mobile No.</label>
                        <select>
                            <option>+91</option>
                            <option>+01</option>
                        </select>
                        <input type="text">
                    </div>
                    <button class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end of add new user subheader --> 

<!-- user list --> 
<div class="container-fluid content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="list-heading">Users List (4)</h1>
            <ul class="list">
                <li class="list-item">
                    <div class="pull-left">
                        <h2 class="list-item-heading">Rahul</h2>
                        <h4>+91 9999967891</h4>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-secondary">Send Message</button>&nbsp;
                        <button class="btn btn-secondary">Change Password</button>
                    </div>
                    <div class="clearfix"></div>
                </li>
                <li class="list-item">
                    <div class="pull-left">
                        <h2 class="list-item-heading">Harish</h2>
                        <h4>+91 9999967891</h4>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-secondary">Send Message</button>&nbsp;
                        <button class="btn btn-secondary">Change Password</button>
                    </div>
                    <div class="clearfix"></div>
                </li>
                <li class="list-item">
                    <div class="pull-left">
                        <h2 class="list-item-heading">Shyam</h2>
                        <h4>+91 9999967891</h4>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-secondary">Send Message</button>&nbsp;
                        <button class="btn btn-secondary">Change Password</button>
                    </div>
                    <div class="clearfix"></div>
                </li>
                <li class="list-item">
                    <div class="pull-left">
                        <h2 class="list-item-heading">Madhav</h2>
                        <h4>+91 9999967891</h4>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-secondary">Send Message</button>&nbsp;
                        <button class="btn btn-secondary">Change Password</button>
                    </div>
                    <div class="clearfix"></div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- end of user list --> 