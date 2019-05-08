<container class="container">
    <row class="row">
        <column class="col-sm-12 col-md-8 col-lg-6 mx-auto text-center">
            <form method="POST" action="/">
                <h1>Welcome to London James!</h1>
                <br />
                <h4 class="mt-3">Where would you like to go?</h4>

                <h4 class="mt-3">By Tube? <input type="radio" name="transport_mode" checked="checked" value="0"></h4>
                <div>
                    <select id="destination_from" name="destination_from">
                        {{destination_list}}
                    </select>
                    <select id="destination_to" name="destination_to">
                        {{destination_list}}
                    </select>
                </div>

                <h4 class="mt-3">Or Bus? <input type="radio" name="transport_mode" value="1"/></h4>
                <div>
                    <label for="bus_destination">Destination:</label>
                    <input type="text" value="" name="bus_destination" size="25"/>
                </div>

                <input type="submit" value="Let's Go!" />
            </form>

        </column>
    </row>
</container>