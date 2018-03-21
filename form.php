<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">User Form</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="user-form" method="post" action="controller.php">
					<div class="form-group">
						<label for="nameInput">Name</label>
						<input type="text" class="form-control" name="name" id="nameInput" placeholder="Enter Name">
					</div>
					<div class="form-group">
						<label for="emailInput">Email address</label>
						<input type="email" class="form-control" name="email" id="emailInput" placeholder="Enter Email">
					</div>
					<div class="form-group">
						<label for="contactInput">Contact</label>
						<input type="type" class="form-control" name="contact" id="contactInput" placeholder="Enter Contact">
					</div>
					<div class="form-group">
						<label for="statusSelect">Status</label>
						<select class="form-control" name="status" id="statusSelect">
							<option value="1">Active</option>
							<option value="2">Unactive</option>
						</select>
					</div>
					<button type="submit" name="submit" value="add" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>