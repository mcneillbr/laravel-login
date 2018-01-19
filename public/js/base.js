$(function() {
    jsGrid.locale('pt-br');

    var createAlert = (function(messages) {
        var html = '<div class="alert alert-warning alert-dismissible" role="alert">';
        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        html += '<strong></strong>' + messages;
        html += '</div>'

        $('#alerts').prepend(html);
    })

    $("#frmUserlogin").validate({

        rules: {

            email: { required: true, email: true },
            password: { required: true, minlength: 6 },
        },
        messages: {

            email: "Preencher o e-mail do usuário",
            password: "Preencher o campos senha corretamente, mínimo de 6 caracteres",

        },
        submitHandler: function() {
            loginUser();
        }
    });

    $('#frmUserlogout').submit(function(evt) {
        evt.preventDefault();
        logoutUser();
        return false;
    });


    var loginUser = (function() {
        $.ajax({
                url: window.location.origin + '/api/v1/login',
                type: 'POST',
                data: $("#frmUserlogin").serializeArray(),

            }).done(function(data) {
                console.debug("done:second success", arguments);
                if (data.state) {
                    createAlert('bem vindo ' + data.user);
                    $('#menuLogoutTitle').text(data.user);
                    $('#menuLogin').addClass('hidden');
                    $('#menuLogout').removeClass('hidden');
                } else {
                    createAlert('falha ao efetuar o login');
                }

            })
            .fail(function(res) {
                console.debug("fail:error", arguments);
                createAlert(res.responseText);
            })
            .always(function() {
                console.debug("always:finished", arguments);
            });
    });
    var logoutUser = (function() {
        $.ajax({
                url: window.location.origin + '/api/v1/logout',
                type: 'GET',
                data: $("#frmUserlogin").serializeArray(),

            }).done(function(data) {
                console.debug("done:second success", arguments);
                createAlert('obrigado');
                $('#menuLogoutTitle').text('no user');
                $('#menuLogout').addClass('hidden');
                $('#menuLogin').removeClass('hidden');
                $('input[name="_token"]').val(data.csrf_token);
                $('meta[name="_token"').attr('content', data['csrf_token']);

            })
            .fail(function(res) {
                console.debug("fail:error", arguments);
                createAlert(res.responseText);
            })
            .always(function() {
                console.debug("always:finished", arguments);
            });
    });

    var deleteUser = (function(user) {
        //console.debug("deleteUser", user);
        var id = user.id || 0;
        $.ajax({
                url: window.location.origin + '/api/v1/user/' + id + '/delete',
                type: 'DELETE',
                data: { '_token': $('meta[name="_token"').attr('content'), 'id': id },

            }).done(function(data) {
                console.debug("done:second success", arguments);
                if (data.deleted) {
                    createAlert('excluido');
                } else {
                    createAlert('falha ao excluir');
                }

            })
            .fail(function(res) {
                console.debug("fail:error", arguments);
                createAlert(res.responseText);
            })
            .always(function() {
                console.debug("always:finished", arguments);
            });
    });
    var sendForm = (function() {
        var id = $('#userDialog input[name="id"]').val();
        var method = ($("#frmUserDialog").data('recordState') === 'new') ? 'POST' : 'PUT';
        var uriSegment = (id.length > 0) ? '/' + id : '/create';
        $.ajax({
                url: window.location.origin + '/api/v1/user' + uriSegment,
                type: method,
                data: $("#frmUserDialog").serializeArray(),

            }).done(function(data) {
                console.debug("done:second success", arguments);
                if (data.saved) {
                    createAlert('salvo');
                } else {
                    createAlert('falha ao salvar');
                }

            })
            .fail(function(res) {
                console.debug("fail:error", arguments);
                createAlert(res.responseText);
            })
            .always(function() {
                console.debug("always:finished", arguments);
            });
    });
    var formSubmitHandler = $.noop;

    var clearUsrFrmDialog = (function() {
        $('#userDialog input[name="id"]').val('');
        $('#userDialog input[name="name"]').val('');
        $('#userDialog input[name="email"]').val('');
        $('#userDialog input[name="password"]').val('');
        $('#userDialog input[name="password_confirmation"]').val('');
        $('#userDialog input[name="state"]').prop("checked", true);
        $('#userDialog input[name="password"]').removeClass('ignore');
        $('#userDialog input[name="password_confirmation"]').removeClass('ignore');
        $("#frmUserDialog").validate().resetForm();
        $("#frmUserDialog").data('recordState', 'none');
    });



    var showUserDialog = function(dialogType, client) {
        var isNewRecord = dialogType === "Add";
        $('#userDialog input[name="id"]').val(isNewRecord ? '' : client.id);
        $('#userDialog input[name="name"]').val(client.name);
        $('#userDialog input[name="email"]').val(client.email);
        $('#userDialog input[name="password"]').val('');
        $('#userDialog input[name="password_confirmation"]').val('');
        $('#userDialog input[name="state"]').prop("checked", (client.state == 1 || client.state === true || client.state === 'true'));

        formSubmitHandler = function() {
            saveClient(client, isNewRecord);
        };
        var title = (isNewRecord) ? "Novo Usuário" : "Editar " + client.name;
        if (!isNewRecord) {
            $('#userDialog input[name="password"]').addClass('ignore');
            $('#userDialog input[name="password_confirmation"]').addClass('ignore');
            $("#frmUserDialog").data('recordState', 'update');
        } else {
            $("#frmUserDialog").data('recordState', 'new');
        }
        $('#userDialog .modal-content .modal-title').text(title);
        $('#userDialog').modal('show');

    };

    var saveClient = function(client, isNew) {
        $.extend(client, {
            id: $('#userDialog input[name="id"]').val(),
            name: $('#userDialog input[name="name"]').val(),
            email: $('#userDialog input[name="email"]').val(),
            password: $('#userDialog input[name="password"]').val(),
            password_confirmation: $('#userDialog input[name="password_confirmation"]').val(),
            state: $('#userDialog input[name="state"]').is(":checked")
        });

        $("#jsGrid").jsGrid(isNew ? "insertItem" : "updateItem", client);

        sendForm();

        $("#userDialog").modal('hide');

    };

    $("#frmUserDialog").validate({
        ignore: ".ignore",
        rules: {
            name: "required",
            email: { required: true, email: true },
            password: { required: true, minlength: 6 },
            password_confirmation: {
                equalTo: "#user_password_confirmation"
            }

        },
        messages: {
            name: "Preencher o nome do usuário",
            email: "Preencher o e-mail do usuário",
            password: "Preencher o campos senha corretamente, mínimo de 6 caracteres",
            password_confirmation: "As senhas devem ser iguais"
        },
        submitHandler: function() {
            formSubmitHandler();
        }
    });

    $("#btnuserDialogSave").on('click', function() {
        $("#frmUserDialog").submit();
    });
    $("#btnuserDialogClose").on('click', function() {

    });

    $('#userDialog').on('hidden.bs.modal', function(e) {
        clearUsrFrmDialog();
    })

    $("#jsGrid").jsGrid({
        width: "80%",
        height: "400px",
        autoload: true,
        inserting: false,
        editing: true,
        sorting: true,
        paging: true,
        deleteConfirm: "Confirma deletar o usuário?",
        pageSize: 10,
        controller: {
            loadData: function() {
                var d = $.Deferred();

                $.ajax({
                    url: window.location.origin + '/api/v1/users',
                    dataType: "json"
                }).done(function(response) {
                    d.resolve(response);
                });

                return d.promise();
            }
        },
        deleteConfirm: function(item) {
            return "Excluir o usuário (\"" + item.name + "\"), deseja continuar?";
        },
        onItemDeleted: function(args) {
            //console.debug("onItemDeleted", arguments);
            deleteUser(args.item);
        },
        rowClick: function(args) {
            showUserDialog("Edit", args.item);
        },
        fields: [
            { title: "Id", name: "id", type: "number", width: 20, validate: "required", editing: false },
            { title: "Nome", name: "name", type: "text", width: 60, validate: "required" },
            { title: "E-mail", name: "email", type: "text", width: 60, validate: "required" },
            { title: "Ativo", name: "state", type: "checkbox", width: 40 },
            {
                type: "control",
                modeSwitchButton: false,
                editButton: false,
                headerTemplate: function() {
                    return $("<button>")
                        .addClass('btn btn-primary')
                        .attr("type", "button")
                        .text("Criar")
                        .on("click", function() {
                            showUserDialog("Add", {});
                        });
                }
            }
        ]
    });
});