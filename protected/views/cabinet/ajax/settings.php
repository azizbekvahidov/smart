
<div class="portlet-title tabbable-line">
    <div class="caption caption-md">
        <i class="icon-globe theme-font hide"></i>
        <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
    </div>
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_1_1" data-toggle="tab">Персональные данные</a>
        </li>
        <li>
            <a href="#tab_1_2" data-toggle="tab">Изменить аватар</a>
        </li>
        <li>
            <a href="#tab_1_3" data-toggle="tab">Изменить пароль</a>
        </li>
    </ul>
</div>
<div class="portlet-body">
    <div class="tab-content">
        <!-- PERSONAL INFO TAB -->
        <div class="tab-pane active" id="tab_1_1">
            <form role="form" id="personalInfo" action="#">
                <div class="form-body">
                    <div class="form-group form-md-line-input">
                        <input type="text" class="form-control" name="name" id="form_control_1" placeholder="Enter your name">
                        <label for="form_control_1">Name
                            <span class="required">*</span>
                        </label>
                        <span class="help-block">Enter your name...</span>
                    </div>
                    <div class="form-group form-md-line-input">
                        <input type="text" class="form-control" name="email" placeholder="Enter your email">
                        <label for="form_control_1">Email
                            <span class="required">*</span>
                        </label>
                        <span class="help-block">Please enter your email...</span>
                    </div>
                    <div class="form-group form-md-line-input">
                        <input type="text" class="form-control"  name="url" placeholder="Enter URL">
                        <label for="form_control_1">URL</label>
                    </div>
                    <div class="form-group form-md-line-input">
                        <input type="text" class="form-control"  name="number" placeholder="Enter number">
                        <label for="form_control_1">Number</label>
                    </div>
                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-envelope"></i>
                                                    </span>
                            <input type="text" class="form-control" name="digits" placeholder="Enter digits">
                            <label for="form_control_1">Digits</label>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                            <input type="text" class="form-control" name="email2" placeholder="Enter your email">
                            <label for="form_control_1">Email</label>
                            <span class="input-group-addon">
                                                        <i class="fa fa-envelope"></i>
                                                    </span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                            <div class="input-group-control">
                                <input type="text" class="form-control" name="number2" placeholder="Placeholder">
                                <label for="form_control_1">Enter your number</label>
                            </div>
                            <span class="input-group-btn btn-right">
                                                        <button type="button" class="btn green-haze dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> Action
                                                            <i class="fa fa-angle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right" role="menu">
                                                            <li>
                                                                <a href="javascript:;">Action</a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;">Another action</a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;">Something else here</a>
                                                            </li>
                                                            <li class="divider"> </li>
                                                            <li>
                                                                <a href="javascript:;">Separated link</a>
                                                            </li>
                                                        </ul>
                                                    </span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <select class="form-control" name="delivery">
                            <option value="">Select</option>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                        </select>
                        <label for="form_control_1">Warning State</label>
                        <span class="help-block">Some help goes here...</span>
                    </div>
                    <div class="form-group form-md-line-input">
                        <textarea class="form-control" name="memo" rows="3"></textarea>
                        <label for="form_control_1">Memo</label>
                        <span class="help-block">Some help goes here...</span>
                    </div>
                    <div class="form-group form-md-checkboxes">
                        <label for="form_control_1">Checkboxes</label>
                        <div class="md-checkbox-list">
                            <div class="md-checkbox">
                                <input type="checkbox" id="checkbox2_1" name="checkboxes1[]" value="1" class="md-check">
                                <label for="checkbox2_1">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 1 </label>
                            </div>
                            <div class="md-checkbox">
                                <input type="checkbox" id="checkbox2_2" name="checkboxes1[]" value="1" class="md-check">
                                <label for="checkbox2_2">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 2 </label>
                            </div>
                            <div class="md-checkbox">
                                <input type="checkbox" id="checkbox2_3" name="checkboxes1[]" value="1" class="md-check">
                                <label for="checkbox2_3">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 3 </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-md-checkboxes">
                        <label for="form_control_1">Checkboxes</label>
                        <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                                <input type="checkbox" id="checkbox2_4" name="checkboxes2[]" value="1" class="md-check">
                                <label for="checkbox2_4">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 1 </label>
                            </div>
                            <div class="md-checkbox">
                                <input type="checkbox" id="checkbox2_5" name="checkboxes2[]" value="1" class="md-check">
                                <label for="checkbox2_5">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 2 </label>
                            </div>
                            <div class="md-checkbox">
                                <input type="checkbox" id="checkbox2_6" name="checkboxes2[]" value="1" class="md-check">
                                <label for="checkbox2_6">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 3 </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-md-radios">
                        <label for="form_control_1">Radios</label>
                        <div class="md-radio-list">
                            <div class="md-radio">
                                <input type="radio" id="checkbox112_6" name="radio1" value="1" class="md-radiobtn">
                                <label for="checkbox112_6">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 1 </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox112_7" name="radio1" value="2" class="md-radiobtn">
                                <label for="checkbox112_7">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 2 </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-md-radios">
                        <label for="form_control_1">Radios</label>
                        <div class="md-radio-inline">
                            <div class="md-radio">
                                <input type="radio" id="checkbox2_8" name="radio2" value="121" class="md-radiobtn">
                                <label for="checkbox2_8">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 1 </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox2_9" name="radio2" value="112" class="md-radiobtn">
                                <label for="checkbox2_9">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 2 </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox2_10" name="radio2" value="112" class="md-radiobtn">
                                <label for="checkbox2_10">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Option 3 </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn green">Validate</button>
                            <button type="reset" class="btn default">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END PERSONAL INFO TAB -->
        <!-- CHANGE AVATAR TAB -->
        <div class="tab-pane" id="tab_1_2">

            <form id="fileInput" role="form" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                        <div>
                        <span class="btn default btn-file">
                            <span class="fileinput-new"> Выберите файл</span>
                            <span class="fileinput-exists"> Изменить </span>
                            <input type="file" id="uploadedFile" name="file">
                        </span>
                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Удалить </a>
                        </div>
                    </div>
                </div>
                <div class="margin-top-10">
                    <button type="button" class="btn green" id="saveFile"> Сохранить </button>
                    <button type="reset" class="btn default"> Отмена </button>
                </div>
            </form>
        </div>
        <!-- END CHANGE AVATAR TAB -->
        <!-- CHANGE PASSWORD TAB -->
        <div class="tab-pane" id="tab_1_3">
            <form id="changePassword">
                <div class="form-group form-md-line-input">
                    <input type="password" class="form-control" name="oldPass" id="oldPass" >
                    <label for="oldPass">Текущий пароль
                        <span class="required">*</span>
                    </label>
                    <span class="help-block">Введите старый пароль.</span>
                </div>
                <div class="form-group form-md-line-input">
                    <input type="password" class="form-control" name="newPass" id="newPass" >
                    <label for="newPass">Новый пароль
                        <span class="required">*</span>
                    </label>
                    <span class="help-block">Введите новый пароль.</span>
                </div>
                <div class="form-group form-md-line-input">
                    <input type="password" class="form-control" name="reNewPass" id="reNewPass" >
                    <label for="reNewPass">Повтор новово пароля
                        <span class="required">*</span>
                    </label>
                    <span class="help-block">Введите новый пароль повторно.</span>
                </div>
                <div class="margin-top-10">
                    <button type="button" class="btn green" id="savePass"> Сохранить </button>
                    <button type="reset" class="btn default" id="cencelPass"> Отмена </button>
                </div>
            </form>
        </div>
        <!-- END CHANGE PASSWORD TAB -->

    </div>
</div>