
@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Journal Detail</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{route('journal-entry.index')}}">Journal Entry</a></li>
                                <li class="breadcrumb-item">{{ \App\Models\Utility::journalNumberFormat($journalEntry->journal_id) }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="text-end">
                <button id="downloadPdf" class="btn btn-sm btn-primary">
                    <i class="fas fa-download"></i>
                </button>
            </div>

            <br>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice">
                                <div class="invoice-print" id="pdfContent">
                                    <div class="row invoice-title mt-2">
                                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                            <h2>Journal</h2>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                                            <h3 class="invoice-number">{{ \App\Models\Utility::journalNumberFormat($journalEntry->journal_id) }}</h3>
                                        </div>
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- <small class="font-style">
                                                <strong>{{__('To')}} :</strong><br>
                                                {{!empty($settings['company_name'])?$settings['company_name']:''}}<br>
                                                {{!empty($settings['company_telephone'])?$settings['company_telephone']:''}}<br>
                                                {{!empty($settings['company_address'])?$settings['company_address']:''}}<br>
                                                {{!empty($settings['company_city'])?$settings['company_city']:'' .', '}}  {{!empty($settings['company_state'])?$settings['company_state']:'' .', '}}  {{!empty($settings['company_country'])?$settings['company_country']:'' .'.'}}
                                            </small> --}}
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <small>
                                                <strong>{{__('Journal No')}} :</strong>
                                                {{\App\Models\Utility::journalNumberFormat($journalEntry->journal_id)}}
                                            </small><br>
                                            <small>
                                                <strong>{{__('Journal Ref')}} :</strong>
                                                {{$journalEntry->reference}}
                                            </small> <br>
                                            <small>
                                                <strong>{{__('Journal Date')}} :</strong>
                                                {{\App\Models\Utility::dateFormat($journalEntry->date)}}
                                            </small>
                                        </div>
                                    </div>
        
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="font-weight-bold">{{__('Journal Account Summary')}}</div>
                                            <div class="table-responsive mt-2">
                                                <table class="table mb-0 ">
                                                    <tr>
                                                        <th data-width="40" class="text-dark">Serial</th>
                                                        <th class="text-dark">{{__('Account')}}</th>
                                                        <th class="text-dark" width="25%">{{__('Description')}}</th>
                                                        <th class="text-dark">{{__('Debit')}}</th>
                                                        <th class="text-dark">{{__('Credit')}}</th>
                                                        <th class="text-dark">{{__('Amount')}}</th>
                                                        <th class="text-dark no-print"></th>
                                                    </tr>
        
                                                    @foreach($accounts as $key =>$account)
        
                                                        <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td>{{!empty($account->accounts)?$account->accounts->code.' - '.$account->accounts->name:''}}</td>
                                                            <td>{{!empty($account->description)?$account->description:'-'}}</td>
                                                            <td>{{\App\Models\Utility::priceFormat($account->debit)}}</td>
                                                            <td>{{\App\Models\Utility::priceFormat($account->credit)}}</td>
                                                            <td >
                                                                @if($account->debit!=0)
                                                                    {{\App\Models\Utility::priceFormat($account->debit)}}
                                                                @else
                                                                    {{\App\Models\Utility::priceFormat($account->credit)}}
                                                                @endif
                                                            </td>
                                                            <td class="no-print">
                                                                <div class="action-btn ms-2">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => array('journal.destroy', $account->id),'id'=>'delete-form-'.$account->id]) !!}
                                                                    <a class="btn btn-sm" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$account->id}}">
                                                                        <i class="fas fa-trash-alt text-danger"></i>
                                                                    </a>
                                                                    <div class="modal fade contentmodal" id="{{'deleteModal-'.$account->id}}" tabindex="-1" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content doctor-profile">
                                                                                <div class="modal-header pb-0 border-bottom-0  justify-content-end">
                                                                                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                                                                                        <i class="fas fa-times"></i>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="delete-wrap text-center">
                                                                                        <div class="del-icon">
                                                                                            <i class="fas fa-trash-alt"></i>
                                                                                        </div>
                                                                                        <input type="hidden" name="id" class="e_id" value="">
                                                                                        <input type="hidden" name="avatar" class="e_avatar" value="">
                                                                                        <h2>Sure you want to delete?</h2>
                                                                                        <div class="submit-section d-flex justify-content-center">
                                                                                            <button type="submit" class="btn btn-success me-2">Yes</button>
                                                                                            <a class="btn btn-danger me-2 d-flex justify-content-center" style="height: unset;" data-bs-dismiss="modal">No</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    {!! Form::close() !!}
        
                                                                </div>
                                                            </td>
                                                        </tr>
        
                                                    @endforeach
        
                                                    <tfoot>
        
                                                    <tr>
                                                        <td colspan="4"></td>
                                                        <td><b>{{__('Total Credit')}}</b></td>
                                                        <td>{{\App\Models\Utility::priceFormat($journalEntry->totalCredit())}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4"></td>
                                                        <td><b>{{__('Total Debit')}}</b></td>
                                                        <td>{{\App\Models\Utility::priceFormat($journalEntry->totalDebit())}}</td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="font-bold mt-2">
                                                {{__('Description')}} : <br>
                                            </div>
                                            <small>{{$journalEntry->description}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
            </div>

        </div>
    </div>


<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    document.getElementById('downloadPdf').addEventListener('click', function() {
        const button = this;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating PDF...';
        button.disabled = true;

        const element = document.querySelector('.card');

        const opt = {
            filename:     'journal-entry-{{ $journalEntry->journal_id }}.pdf',
            image:       { type: 'jpeg', quality: 0.98 },
            html2canvas: {
                scale: 2,    
                scrollY: 0,       
                windowHeight: element.scrollHeight, 
                logging: true,
                useCORS: true,
                allowTaint: true
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait',
                putOnlyUsedFonts: true,
                compress: true
            }
        };

        html2pdf()
            .set(opt)
            .from(element)
            .save()
            .then(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            })
            .catch((error) => {
                console.error('PDF generation failed:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Failed to generate PDF. Please try again.');
            });
    });
</script>
   
@endsection

