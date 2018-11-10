<h2>News Table</h2>
<div class="alert alert-info">
    <strong>Pdf Reports</strong>
</div>
<p></p>
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Id</th>
            <th>User Name</th>
            <th>User Email</th>
            <th>Last Login</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($all_user as $value) : ?>
        <tr>
            <td><?php $value['id']; ?></td>
            <td><?php $value['username']; ?></td>
            <td><?php $value['user_email']; ?></td>
            <td><?php $value['last_login']; ?></td>
            <td><?php $value['status']; ?></td>
        </tr>
        </tbody>
        <?php endforeach ?>
    </table>
</div>