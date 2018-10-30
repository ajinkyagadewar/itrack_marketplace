function delete_extra(deleteid) {
	var delete1 = deleteid;
	console.log(delete1);
	var ans = confirm("Do you really want to delete Course Extra Settings?");
	
	if (ans) {
		window.location.href= page.url+"/local/course_extrasettings/delete.php?general=" + delete1;
	} else {
		location.reload();
	}
}
