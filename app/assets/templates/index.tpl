<container class="container">
    <row class="row">
        <column class="col-sm-12 col-md-8 col-lg-6 mx-auto text-center">

            <h1>Welcome to London James!</h1>
            <br />
            <messages>
                {{messages}}
            </messages>
            <hr />
            <form method="POST" action="/">
                <p class="mt-3">Your current balance is: &pound;{{account_balance}}</p>
                <p class="mt-3">Top up: <input type="text" value="10.00" name="topup_amount" /><button>Topup!</button></p>
            </form>
            <hr />
            <form method="POST" action="/">
                <h4 class="mt-3">Where would you like to go?</h4>

                <h4 class="mt-3">By Tube? <input type="radio" name="transport_mode" value="0" {{transport_mode_0_selected}}></h4>
                <div>
                    <select id="destination_from" name="destination_from">
                        {{from_list}}
                    </select>
                    <select id="destination_to" name="destination_to">
                        {{to_list}}
                    </select>
                </div>

                <h4 class="mt-3">Or Bus? <input type="radio" name="transport_mode" value="1" {{transport_mode_1_selected}}/></h4>
                <div>
                    <label for="bus_destination">Destination:</label>
                    <input type="text" value="{{bus_destination}}" name="bus_destination" size="25"/>
                </div>

                <input type="submit" value="Let's Go!" />
            </form>

        </column>
    </row>
</container>