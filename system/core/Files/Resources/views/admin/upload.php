<form enctype="multipart/form-data" method="POST">
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h2 style="display:inline-block">Upload File(s)</h2>
         <div class="pull-right" style="margin:20px 0;">
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <input multiple type="file" name="file[]">
        <p class="help-block">Please select file to upload.</p>
        <input type="submit" class="btn btn-primary pull-right" value="Submit" >
    </div>
    <!-- /.col-lg-12 -->
</div>
</form>

<form action="file-echo2.php" method="post" enctype="multipart/form-data">
        <input type="file" name="myfile[]"><br>
        <input type="file" name="myfile[]"><br>
        <input type="file" name="myfile[]"><br>
        <input type="submit" value="Upload File to Server">
    </form>
    
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>