
    function courseReset()
    {
        //resets the course select tag and trigger change 
        $("#courses_select option[value='0']").prop('selected', true);
        $("#users_select").html("<option value='0'>Select User</option>");
    }

    function fetchUsers(path, course)
    {
        $.ajax({
            type: "POST",
            url: path,
            data: {id:course},
            success: function(jsonData)
            {
               
                if (jsonData.success)
                {
                   $("#users_select").html(jsonData.response.html);
                }
                else
                {
                    console.error(jsonData.error);
                }
           }
       });
    }


