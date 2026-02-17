$('#lead_search').click(function () {
    let search = $('#lead_value').val();
    if (search != '') {
        $("searchDTtable_container").html('<table class="table table-striped table-hoverable table-hover w-100" id="searchDTtable"></table>');
        $("#searchDTtable").dataTable({
            ajax: "/leads/lead/fetch_search_summary?search=" + search,
            columns: [
                {
                    "title": "#",
                    "data": "Cust_Key",
                },
                {
                    "title": "English Name",
                    "data": "Cust_EngName",
                },
                {
                    "title": "Arabic Name",
                    "data": "Cust_ArbName"
                },
                {
                    "title": "Customer Mobile",
                    "data": "Cust_Mobile"
                },
                {
                    "title": "Customer Code",
                    "data": "Cust_Code"
                },
                {
                    "title": "Nationality",
                    "data": "Nationality"
                },
                {
                    "title": "Customer Address",
                    "data": "Cust_Address"
                },
                {
                    "title": "Customer Email",
                    "data": "Cust_Email"
                },
            ],
            "createdRow": function (row) {
                $(row).addClass('selected_row');
            },
            destroy: true
        });
        $('#searchDTtable thead').addClass('table-info');
        $("#data_search").modal();
    } else {
        $('.selected_field').attr('readonly', false);
        $('#lead_country_code').attr('readonly', false);
        $('.selected_field').val('');
    }
});

$(document).on('click', '.selected_row', function () {
    let rowData = [];
    $(this).find("td").each(function () {
        rowData.push($(this).text());
    });
    $('#cust_id').val(rowData[0]);
    $('#lead_name').val(rowData[1]);
    $('#lead_contact').val(rowData[3]);
    $('#email').val(rowData[7]);
    $('#lead_country_code').attr('readonly', true);
    $('.selected_field').each(function () {
        console.log($(this).val());
        let value = ($(this).val() == '') ? false : true;
        $(this).attr('readonly', value);
    });
    $("#data_search").modal("hide");
    $('#is_corporate').val('Corporate');
    $('#applicant_name_group').show();
    $('#applicant_name').attr('required', true);
});