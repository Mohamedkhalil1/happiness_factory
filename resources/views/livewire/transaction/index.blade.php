<div>
    @section('title',$pageTitle)
    <x-base.card title="{{ $pageTitle }}">
        {{--ACTIONS--}}
        <x-base.grid class="mb-3">
            <x-base.grid-col col="4">
                <x-form.input lazy="true"
                              type="text"
                              class="round"
                              name="search"
                              placeholder="Search Transactions..."/>
            </x-base.grid-col>

            <x-base.grid-col col="8">
                <x-base.button
                    wire:click="create"
                    style="float: right"
                    data-bs-toggle="modal"
                    data-bs-target="#transaction">
                    <x-icons.add/>
                </x-base.button>
            </x-base.grid-col>
        </x-base.grid>

        {{--Table--}}
        <x-base.table class="border">
            <x-slot name="head">
                <x-table.heading :sortable="true" wire:click="sortBy('title')" id="title" :direction="$sortDirection">
                    Title
                </x-table.heading>

                <x-table.heading :sortable="true" wire:click="sortBy('amount')" id="amount" :direction="$sortDirection">
                    Amount
                </x-table.heading>

                <x-table.heading :sortable="true" wire:click="sortBy('status')" id="status" :direction="$sortDirection">
                    Status
                </x-table.heading>

                <x-table.heading :sortable="true" wire:click="sortBy('date')" id="date" :direction="$sortDirection">
                    Date
                </x-table.heading>

                <x-table.heading :sortable="false">
                    Actions
                </x-table.heading>
            </x-slot>

            <x-slot name="body">
                @forelse($transactions as $transaction)
                    <x-table.row>
                        <x-table.cell>
                            <x-icons.money/>
                            {{ $transaction->title }}
                        </x-table.cell>

                        <x-table.cell>{{ formatMoney($transaction->amount) }} </x-table.cell>

                        <x-table.cell>
                            <x-base.badge type="{{\App\Enums\Status::getColor($transaction->status)  }}">
                                {{ \App\Enums\Status::name($transaction->status) }}
                            </x-base.badge>
                        </x-table.cell>

                        <x-table.cell>{{ formatDate($transaction->date) }}</x-table.cell>
                        <x-table.cell>
                              <span wire:click="edit({{$transaction->id}})" style="cursor: pointer"
                                    data-bs-toggle="modal" data-bs-target="#transaction">
                                  <x-icons.edit/>
                              </span>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="4">
                            <div class="text-center text-muted text-uppercase">
                                No transactions found...
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-base.table>
        <div>
            <nav aria-label="Page navigation example">
                <ul class="pagination pagination-primary">
                    {{ $transactions->links() }}
                </ul>
            </nav>
        </div>
    </x-base.card>

    <x-base.modal id="transaction" size="lg" formAction="updateOrCreate">
        <x-slot name="title">
            Transaction
        </x-slot>
        <x-slot name="content">
            <x-base.grid>
                <div class="col-md-4">
                    <x-form.label :required="false" title="Title"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="text" :required="false" lazy="true" class="round" name="transaction.title"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Amount" :required="false"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-form.input :required="false" lazy="true" name="transaction.amount" type="amount"
                                  inputGroupText="EGP"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label :required="false" title="Date"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.date-time id="date" name="transaction.date" wire:model="transaction.date" type="text"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label :required="false" title="Status"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.select
                        value="{{ $transaction->status }}"
                        name="transaction.status"
                        :options="\App\Enums\Status::keyValue()"
                        selectTitle="Select Status"
                        wire:model="transaction.status"
                    />
                </x-form.form-group>
            </x-base.grid>
        </x-slot>
        <x-slot name="footer">
            <x-base.button type="submit" @click="document.getElementById('form-id').submit()">Save</x-base.button>
        </x-slot>

    </x-base.modal>

</div>


