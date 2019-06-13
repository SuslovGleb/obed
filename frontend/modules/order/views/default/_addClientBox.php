<div class="addClientBox">
    <div class="addClient">
        <form role="form" class="form-horizontal" id="addClient">


            <div class="clientInf addingBox">
                <div class="addClientBoxTitle">Информация</div>

                <div class="formSelectBox clienTelephone inactive form-group has-feedback"
                     divVal="select" inpSelVal="clienTelephone">
                    <select class="telephoneSelect form-control"></select>
                    <div class="divBtn glyphicon glyphicon-plus inpToSelectBtn"></div>
                    <span class="glyphicon form-control-feedback"></span>
                </div>
                <div class="formInputBox clienTelephone form-group has-feedback"
                     divVal="input" inpSelVal="clienTelephone">
                    <input required="required" class="phone form-control inputMain toSelect"
                           type="text" name="clienTelephone"
                           pattern="(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?"
                           placeholder="Телефон клиента"
                           data-content="Введите телефон полностью"
                           data-placement="top"
                    />
                    <div class="divBtn glyphicon inpToSelectBtn addressInput glyphicon-ok"></div>
                    <!--                                                        <span class="glyphicon form-control-feedback"></span>-->
                </div>


                <div class="clienName"><input class="form-control inputMain"
                                              type="text" name="clienName"
                                              placeholder="Имя клиента"></div>
            </div>

            <div class="clientFirmBox addingBox">
                <div class="addClientBoxTitle">Фирма</div>

                <div class="btn btn-primary chooseFirmFromGis">Добавить фиру через 2GIS</div>
                <div class="addFromGisBlock inactive">
                                                         <textarea style="
                                                                        width: 80%;
                                                                        float:  left;
                                                                        margin-bottom:  10px;
                                                                    "></textarea>
                    <div class="btn btn-warning addFirmFromGis">Добавить</div>

                </div>
                <div class="clientFirm">
                    <div class="form-group has-feedback">
                        <input class="search form-control inputMain clientFirm"
                               required="required" pattern="^[\D\d]{4,}$"
                               type="text" name="clientFirm"
                               placeholder="Фирма клиента">
                        <span class="glyphicon form-control-feedback"></span>

                    </div>
                </div>

                <div class="firmAddress inactive">
                    <div class=" formSelectBox selectAddressBox inactive form-group has-feedback"
                         divVal="select" inpSelVal="clientAddr">
                        <select class="addressSelect form-control"></select>
                        <div class="divBtn glyphicon glyphicon-plus inpToSelectBtn"></div>
                        <span class="glyphicon form-control-feedback"></span>
                    </div>
                    <div class=" formInputBox inputAddressBox form-group has-feedback"
                         divVal="input" inpSelVal="clientAddr" minLenght="6">
                        <input class="clientAddr address form-control search toSelect"
                               required="required" pattern="^[\D\d]{6,}$"
                               type="text" placeholder="Адрес"
                               name="clientAddr"
                               data-placement="top"


                               data-content="Адрес должен состоять не менее чем из 6 символов"/>

                        <div class="divBtn glyphicon inpToSelectBtn addressInput glyphicon-ok"></div>
                    </div>

                </div>
                <div class="firmSphere inactive">
                    <div class=" formSelectBox selectSphereBox inactive form-group has-feedback"
                         divVal="select" inpSelVal="firmSphere">
                        <select class="sphereSelect form-control"></select>
                        <div class="divBtn glyphicon glyphicon-plus inpToSelectBtn"></div>
                        <span class="glyphicon form-control-feedback"></span>
                    </div>
                    <div class=" formInputBox inputSphereBox form-group has-feedback"
                         divVal="input" inpSelVal="firmSphere">
                        <input class="sphere form-control search inputMain firmSphere toSelect"
                               pattern="^[\D]{6,}$"
                               type="text" placeholder="Сфера фирмы"
                               name="firmSphere"
                               data-placement="top"


                               data-content="Не менее чем из 6 символов"/>

                        <div class="divBtn glyphicon inpToSelectBtn addressInput glyphicon-ok"></div>
                    </div>

                </div>
            </div>
            <div class="clienComment addingBox">
                <div class="addClientBoxTitle">Коментарий</div>
                <div class="addrComment"><textarea class="form-control inputMain"
                                                   id="exampleFormControlTextarea1"
                                                   rows="4" name="addrComment"
                                                   placeholder="Коментарий"
                                                   style=""></textarea></div>
            </div>


            <div class="btn btn-primary btnSearchClient">Выбрать клиента</div>
            <div class="btn btnAddClient">Добавить данные</div>
        </form>
    </div>

</div>