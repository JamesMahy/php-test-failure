<container class="container">
    <row class="row m-0">
        <column class="col-6 mx-auto text-center">

            <div class="jumbotron jumbotron-fluid">
                <div class="container">
                    <h1 class="display-4">Where would you like to go today?</h1>
                </div>
            </div>

            <messages>
                {{messages}}
            </messages>
            <hr />

            <container class="container-fluid">
                <row class="row">
                    <column class="col-8 mx-auto text-center">
                        <form method="POST" action="/">
                            <container class="container">
                                <row class="row">
                                    <column class="col-6 mx-auto text-center">
                                        <h4>Credit</h4>
                                        <p class="mt-3">Your current balance is: &pound;{{account_balance}}</p>
                                        <div class="form-inline">
                                            <input type="text" value="10.00" name="topup_amount" class="form-control" /><button class="btn btn-primary" role="button">Topup!</button>
                                        </div>
                                    </column>
                                </row>
                            </container>
                        </form>
                        <hr />
                        <form method="POST" action="/">
                            <h4 class="mt-3">Travel</h4>

                            <h4 class="mt-3">By Tube? <input type="radio" name="transport_mode" value="0" {{transport_mode_0_selected}}></h4>
                            <div>
                                <select id="destination_from" name="destination_from" class="form-control">
                                    {{from_list}}
                                </select>
                                <select id="destination_to" name="destination_to" class="form-control">
                                    {{to_list}}
                                </select>
                            </div>

                            <h4 class="mt-3">Or Bus? <input type="radio" name="transport_mode" value="1" {{transport_mode_1_selected}}/></h4>
                            <div>
                                <label for="bus_destination">To Where?:</label>
                                <input type="text" value="{{bus_destination}}" name="bus_destination" size="25"  class="form-control"/>
                            </div>

                            <br />
                            <input type="submit" value="Let's Go!" class="btn btn-success" role="button"/>
                        </form>
                    </column>
                </row>
            </container>

        </column>
    </row>
</container>