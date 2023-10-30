<div class="table-responsive custom-table">
    <table class="table table-borderless table-striped">
        <tbody>
            <tr>
                <td>
                    <strong>User Name</strong>
                    <p class="mb-0">{{ $data->name }}</p>
                </td>
                <td>
                    <strong>Email</strong>
                    <p class="mb-0">{{ $data->email }}</p>
                </td>
                <td>
                    <strong>Business Category</strong>
                    <p class="mb-0">{{ $data->business_type }}</p>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Accepted Payment Methods</strong>
                    <br>
                    @if($data->accept_card != null )
                    <?php if(is_array(json_decode($data->accept_card, true))) { ?>
                    @foreach (json_decode($data->accept_card) as $item )
                    <span class='badge badge-sm badge-primary'>{{ $item }}</span>
                    @endforeach
                    <?php    } else { ?>
                    <span class='badge badge-sm badge-primary'>{{ $data->accept_card }}</span>
                    <?php } ?>
                    @endif
                </td>
                <td>
                    <strong>Company Name</strong>
                    <p class="mb-0">{{ $data->business_name }}</p>
                </td>
                <td>
                    <strong>Your Website URL</strong>
                    <p class="mb-0">{{ $data->website_url }}</p>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>First Name</strong>
                    <p class="mb-0">{{ $data->business_contact_first_name }}</p>
                </td>
                <td>
                    <strong>Last Name</strong>
                    <p class="mb-0">{{ $data->business_contact_last_name }}</p>
                </td>
                <td>
                    <strong>Residential Address</strong>
                    <p class="mb-0">{{ $data->residential_address }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Company Address</strong>
                    <p class="mb-0">{{ $data->business_address1 }}</p>
                </td>
                <td>
                    <strong>Country Of Incorporation</strong>
                    <p class="mb-0">{{ $data->country }}</p>
                </td>
                <td>
                    <strong>Phone Number</strong>
                    <p class="mb-0">+{{ $data->country_code }} {{ $data->phone_no }}</p>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Contact Details</strong>
                    <p class="mb-0">{{ $data->skype_id }}</p>
                </td>
                <td>
                    <strong>Processing Currency</strong>
                    <br>
                    @if($data->processing_currency != null)
                    <?php
                        $a = json_decode($data->processing_currency);
                        foreach ($a as $key => $value) {
                            echo "<span class='badge badge-sm badge-primary'>".$value."</span> ";
                        }
                    ?>
                    @endif
                </td>
                <td>
                    <strong>Integration Preference</strong>
                    <br>
                    @if($data->technology_partner_id != null)
                    <?php
                        $a = json_decode($data->technology_partner_id);
                        foreach ($a as $key => $value) {
                            echo "<span class='badge badge-sm badge-primary'>".getTechnologyPartnerName($value)."</span> ";
                        }
                    ?>
                    @endif
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Processing Country</strong>
                    <br>
                    @if($data->processing_country != null)
                    <?php
                        $a = json_decode($data->processing_country);
                        foreach ($a as $key => $value) {
                            if($value != 'Others'){
                                echo "<span class='badge badge-sm badge-primary'>".$value."</span> ";
                            }
                        }
                    ?>
                    @endif
                    @if($data->other_processing_country != null)
                    <br>
                    {{ $data->other_processing_country }}
                    @endif
                </td>
                <td>
                    <strong>Industry Type</strong>
                    <br>
                    @php
                    $categoryName = getCategoryName($data->category_id);
                    @endphp
                    @if(isset($data->category_id))
                    @if($categoryName != 'Miscellaneous')
                    <span class='badge badge-sm badge-primary'>{{ $categoryName }}</span>
                    @else
                    @if($data->other_industry_type != null)
                    <span class="badge badge-primary badge-sm">{{ $data->other_industry_type }}</span>
                    @endif
                    @endif
                    @else
                    ---
                    @endif
                </td>
                <td>
                    <strong>License Status</strong>
                    <p class="mb-0">
                    @if($data->company_license == 0)
                    Licensed
                    @elseif($data->company_license == 1)
                    Unlicensed
                    @elseif($data->company_license == 2)
                    NA
                    @else
                    ---
                    @endif
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Monthly Volume</strong>
                    <p class="mb-0">{{ $data->monthly_volume_currency }} {{ $data->monthly_volume }}</p>
                </td>

                <td>
                    <strong>Number of Directors</strong><br>
                    <span class="badge badge-primary badge-sm">{{ $data->board_of_directors }}</span>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>