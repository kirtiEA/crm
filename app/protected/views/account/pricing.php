<?php $this->widget('LoginModal'); ?>
<!-- pricing content -->
<div class="row pricing-content">
    <div class="col-md-12">
        <div class="pricing-content-headings">
            <h1>Pricing designed for you</h1>
            <h3>Create your account, set-up your campaigns, download the Mobile app, <span class="emphasis-text">all for free.</span> Only pay for certified images. Customised branding if also available. </h3>
        </div>
        <div class="pricing-tables-wrap">
            <table class="table table-bordered pricing-table">
                <thead>
                    <tr>
                        <th class="free-features-head"><h2>Access All This for Free</h2></th>
                    </tr>
                </thead>
                <tbody class="free-features-body">
                    <tr>
                        <td>Campaign Dashboard</td>
                    </tr>
					<tr>
						<td>Mobile App</td>
					</tr>
					<tr>
						<td>Flexible Task Assignment</td>
					</tr>
					<tr>
						<td>Instant Alerts</td>
					</tr>
					<tr>
						<td>Campaign Reports</td>
					</tr>
                </tbody>
            </table>
            <table class="table table-bordered pricing-table">
                <thead>
                    <tr>
                        <th><h3>Pay-As-You-Go</h3></th>
						<th>
							<h3>Enterprise</h3> 
							<h4>(Best for x,xxx+ images per month)</h4>
						</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
							<h3>Branded Campaign Reports</h3> 
							<h4>$45 per month</h4>
                        </td>
						<td>
							<h3>Branded</h3> 
							<h4>yourcompany.com</h4>
						</td>
                    </tr>
					<tr>
                        <td>
							<h3>Certified Images</h3> 
							<h4>USD X,XXX per 1,000 credits</h4>
                        </td>
                        <td>
							<h3>Certified Images</h3> 
							<h4>Unlimited Credits</h4>
                        </td>
					</tr>
					<tr>
						<td></td>
						<td>
							<h3>Annual Subscription Price</h3>
							<h4><a href="<?php echo Yii::app()->urlManager->createUrl('account/contactus'); ?>">Contact Us</a></h4>
						</td>
					</tr>
					<tr>
						<td><button class="btn btn-primary btn-primary-lg js-signup-btn">Sign Up</button></td>
						<td><button class="btn btn-primary btn-primary-lg js-contactus-btn">Contact Us</button></td>
					</tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- end of pricing content -->
<script>
    $(function(){
        $('li.phone1').css({
            "margin-top": "15px",
            "font-size": "16px",
            "font-weight": "600",
            "margin-right": "10px"
        });
        $('#header_nav').removeClass('navbar-dark');
    });    
</script>
