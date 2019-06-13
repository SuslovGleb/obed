<form class="addClientInputs">
    <div class=" formInputBox inputClientFirm form-group has-feedback"
         divVal="input" inpSelVal="clientFirm" minLenght="4">
            <div class="form-group has-feedback">
                <input class="search form-control inputMain clientFirm"
                       required="required" pattern="^[\D\d]{4,}$"
                       type="text" placeholder="Фирма клиента"
                       name="clientFirm"
                       data-placement="left"
                       value=""
                       data-content="Наименование фирмы должно состоять не менее чем из 4 символов"/>

                <span class="glyphicon form-control-feedback"></span>
            </div>
        <input type="checkbox" class="NotAFirm" name="NotAFirm" style="
    float:  left;
    width:  10%;
    /* line-height:  67px; */
    margin-top:  11px;
    margin-left: -26px;
">
        <label for="NotAFirm" style="
    float:  left;
    line-height:  35px;
    margin-left:  -27px;
    color: wheat;
">Без фирмы</label>
    </div>
    <div class=" formInputBox inputClientName form-group has-feedback"
         divVal="input" inpSelVal="clientName">
        <input class="clientName form-control "
               type="text" placeholder="Имя клиента"
               name="clientName"
               value=""/>
        <span class="glyphicon form-control-feedback"></span>
    </div>
    <div class=" formInputBox inputAddressBox form-group has-feedback"
         divVal="input" inpSelVal="clientAddr" minLenght="6">
        <input class="clientAddr address form-control search "
               required="required" pattern="^[\D\d]{6,}$"
               type="text" placeholder="Адрес"
               name="clientAddr"
               data-placement="left"
               value=""
               data-content="Адрес должен состоять не менее чем из 6 символов"/>
        <span class="glyphicon form-control-feedback"></span>
    </div>
    <div class=" formInputBox inputComentBox form-group has-feedback"
         divVal="input" inpSelVal="clientAddrComm" >
        <select name="officeRoom" class="form-control officeRoom">
            <option value="office">Офис</option>
            <option value="room">Квартира</option>
            <option value="else">Другое</option>
        </select>
        <input class="clientAddrComm AddrComm form-control "
               type="text" placeholder="Квартира\Офис\Другое"
               name="clientAddrComm"
               value=""/>
        <span class="glyphicon form-control-feedback"></span>
    </div>
    <div class="formInputBox clienTelephone form-group has-feedback"
         divVal="input" inpSelVal="clienTelephone">
        <input required="required" class="phone form-control inputMain "
               type="text" name="clienTelephone"
               pattern="(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?"
               placeholder="Телефон клиента"
               data-content="Введите телефон полностью"
               data-placement="left"
               value="<?= $inputVal ?>"
        />
        <span class="glyphicon form-control-feedback"></span>
    </div>
    <div class="btn btnAddClient">Добавить Клиента</div>
</form>