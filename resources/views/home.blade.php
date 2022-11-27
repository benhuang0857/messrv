@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">任務</div>
                <div class="panel-heading">
                    {{-- <a href="?show=all"><span class="badge badge-primary" style="padding:10px">所有</span></a> --}}
                    <a href="?show=approve"><span class="badge badge-primary" style="padding:10px">等待加工({{$Data['JobNotStartCount']}})</span></a>
                    <a href="?show=process"><span class="badge badge-primary" style="padding:10px">進行中({{$Data['JobProcessCount']}})</span></a>
                    <a href="?show=complete"><span class="badge badge-primary" style="padding:10px">完成({{$Data['JobCmpleteCount']}})</span></a>
                    <a href="?show=starthold"><span class="badge badge-primary" style="padding:10px">暫停({{$Data['JobHoldCount']}})</span></a>
                </div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>歡迎您 {{$Data['User']->name}}</h4>

                    <div class="panel-group" id="accordion">
                        @foreach ($Data['Batches'] as $batch)
                            <?php 
                                $background = 'style=background:none';
                            ?>
                            @if($batch->state == 'process')
                                <?php 
                                    $background = 'style=background:#e0ffe0';
                                ?>
                            @elseif($batch->state == 'starthold')
                                <?php 
                                    $background = 'style=background:#f8e3e3';
                                ?>
                            @elseif($batch->state == 'endhold')
                                <?php 
                                    $background = 'style=background:#e0ffe0';
                                ?>
                            @elseif($batch->state == 'complete')
                                <?php 
                                    $background = 'style=background:#cbcbcb';
                                ?>
                            @endif

                            @if (!isset($_GET['show']))
                                <?php
                                    $_GET['show'] = 'approve';
                                ?>
                            @endif

                            @if ($_GET['show'] == $batch->state)
                                <div class="panel panel-default" {!!$background!!}>
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" 
                                            href="#collapse{{$batch->id}}">
                                            <p><span class="badge badge-secondary" style="background:<?php echo $Data['Color'][$batch->state]?>">{{$Data['States'][$batch->state]}}</span></p>
                                            <p>訂單建立時間：<?php
                                                try {
                                                    echo $batch->Runs->Order->created_at;
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>製程與產品: <?php
                                                try {
                                                    echo $batch->ProdProcessesList->Processes->process_code.$batch->ProdProcessesList->Products->product_name;
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>數量：<?php
                                                try {
                                                    echo $batch->quantity;
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>總時間：<?php
                                                try {
                                                    echo $batch->run_second.'秒(約等於'.round(($batch->run_second/60), 2).'分鐘)';
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>總休息：<?php
                                                try {
                                                    $startrecords = App\BatchStateRecord::where('batch_id', $batch->id)
                                                                                    ->where('state', 'starthold')->get();
                                                    $eedrecords = App\BatchStateRecord::where('batch_id', $batch->id)
                                                                                    ->where('state', 'endhold')->get();
                                                    $restSec = 0;
                                                    for ($i=0; $i < sizeof($startrecords); $i++) { 
                                                        $start = $startrecords[$i]->created_at;
                                                        $end = $eedrecords[$i]->created_at;
                                                        $restSec += $start->diffInSeconds($end);
                                                    }

                                                    echo $restSec.'秒(約等於'.round(($restSec/60), 2).'分鐘)';
                                                } catch (\Throwable $th) {
                                                    echo "--";
                                                }
                                            ?></p>
                                            <p>單位工時：<?php
                                                try {
                                                    $batch_id = $batch->id;
                                                    $pice = App\Batches::where('id', $batch_id)->first()->quantity;
                                                    $run_second = App\Batches::where('id', $batch_id)->first()->run_second;

                                                    $startrecords = App\BatchStateRecord::where('batch_id', $batch_id)
                                                                                    ->where('state', 'starthold')->get();
                                                    $eedrecords = App\BatchStateRecord::where('batch_id', $batch_id)
                                                                                    ->where('state', 'endhold')->get();
                                                    $restSec = 0;
                                                    for ($i=0; $i < sizeof($startrecords); $i++) { 
                                                        $start = $startrecords[$i]->created_at;
                                                        $end = $eedrecords[$i]->created_at;
                                                        $restSec += $start->diffInSeconds($end);
                                                    }

                                                    echo round(($pice/($run_second - $restSec)),2)  .'秒';
                                                } catch (\Throwable $th) {
                                                    echo "--";
                                                }
                                            ?></p>
                                            <p>標準工時：<?php
                                                try {
                                                    echo $batch->ProdProcessesList->process_time.'秒';
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>員工：<?php
                                                try {
                                                    echo $batch->Doer->name;
                                                } catch (\Throwable $th) {
                                                    echo '尚未指派';
                                                }
                                            ?></p>
                                            <p>加工編號：<?php
                                                try {
                                                    echo $batch->run_id;
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>箱號：<?php
                                                try {
                                                    echo $batch->batch_code;
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            </a>
                                        </h4>
                                    </div>
                                    @if ($batch->state == 'complete')
                                    <div id="collapse{{$batch->id}}" class="panel-collapse collapse out">
                                    @else
                                    <div id="collapse{{$batch->id}}" class="panel-collapse collapse in">
                                    @endif
                                        <div class="panel-body">
                                        <form>
                                            <input type="text" name="batchid" class="form-control" value="{{$batch->id}}" style="display:none" readonly>
                                            <div class="form-group">
                                                <label for="order">製程順序</label>
                                                <input type="text" name="order" class="form-control" value="{{$batch->ProdProcessesList->Processes->process_code}}" readonly>
                                            </div>
                                            <div class="form-group" style="display:none">
                                                <label for="tool">機台</label>
                                                <select class="form-control" name="tool">
                                                <option value="TEST">測試機台</option>
                                                {{-- <option value="Ng">未設定</option> --}}
                                                {{-- @foreach ($Data['Tools'] as $tool)
                                                    @if ($tool->name == $batch->tool)
                                                        <option value="{{$tool->name}}" selected>{{$tool->name}}</option>
                                                    @else
                                                        <option value="{{$tool->name}}">{{$tool->name}}</option>
                                                    @endif
                                                @endforeach --}}
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="tool">員工</label>
                                                <select class="form-control" name="doer_id">
                                                <option value="Ng">未設定</option>
                                                @foreach ($Data['Doers'] as $doer)
                                                    @if ($doer->id == Auth::user()->id)
                                                        <option value="{{$doer->id}}" selected>{{$doer->name}}</option>
                                                    @else
                                                        <option value="{{$doer->id}}">{{$doer->name}}</option>
                                                    @endif
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="start_time">開始時間</label>
                                                @if ($batch->start_time != '1000-01-01 00:00:00')
                                                    <input type="datetime" name="start_time" class="form-control" value="{{$batch->start_time}}" readonly>
                                                @else
                                                    <input type="text" name="start_time" class="form-control" value="--" readonly>
                                                @endif                                            
                                            </div>
                                            <div class="form-group">
                                                <label for="end_time">結束時間</label>
                                                @if ($batch->end_time != '1000-01-01 00:00:00')
                                                    <input type="datetime" name="end_time" class="form-control" value="{{$batch->end_time}}" readonly>
                                                @else
                                                    <input type="text" name="end_time" class="form-control" value="--" readonly>
                                                @endif    
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">執行數量</label>
                                                <input type="number" name="quantity" class="form-control" value="{{$batch->quantity}}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="scrap">報廢</label>
                                                <input type="number" name="scrap" class="form-control" value="{{$batch->scrap}}" >
                                            </div>

                                            <div class="form-group" style="display:none">
                                                <label for="hold_reason">暫停原因</label>
                                                <select class="form-control" name="hold_reason">
                                                    <option value="None">無</option>
                                                    <option value="機台PM">機台PM</option>
                                                    <option value="機台異常">機台異常</option>
                                                    <option value="用餐">用餐</option>
                                                    <option value="休息">休息</option>
                                                </select>
                                            </div>                                        
                                            <?php
                                            
                                            $buttonHtm = '
                                            <a type="submit" class="btn btn-primary" onclick="process(this)">執行</a>
                                            <a type="submit" class="btn btn-danger" disabled>開始暫停</a>
                                            <a type="submit" class="btn btn-danger" disabled>結束暫停</a>
                                            <a type="submit" class="btn btn-success" onclick="complete(this)">完成</a>
                                            ';
                                            if ($batch->state == 'starthold') {
                                                $buttonHtm = '
                                                <a type="submit" class="btn btn-primary" disabled>執行</a>
                                                <a type="submit" class="btn btn-danger" disabled>開始暫停</a>
                                                <a type="submit" class="btn btn-danger" onclick="endhold(this)">結束暫停</a>
                                                <a type="submit" class="btn btn-success" disabled>完成</a>
                                                ';
                                            }
                                            else if($batch->state == 'endhold'){
                                                $buttonHtm = '
                                                <a type="submit" class="btn btn-primary" onclick="process(this)">執行</a>
                                                <a type="submit" class="btn btn-danger" onclick="starthold(this)">開始暫停</a>
                                                <a type="submit" class="btn btn-danger" disabled>結束暫停</a>
                                                <a type="submit" class="btn btn-success" onclick="complete(this)">完成</a>
                                                ';
                                            }
                                            else if($batch->state == 'process'){
                                                $buttonHtm = '
                                                <a type="submit" class="btn btn-primary" disabled>執行</a>
                                                <a type="submit" class="btn btn-danger" onclick="starthold(this)">開始暫停</a>
                                                <a type="submit" class="btn btn-danger" disabled>結束暫停</a>
                                                <a type="submit" class="btn btn-success" onclick="complete(this)">完成</a>
                                                ';
                                            }
                                            else if($batch->state == 'complete'){
                                                $buttonHtm = '
                                                <a type="submit" class="btn btn-primary" disabled>執行</a>
                                                <a type="submit" class="btn btn-danger" disabled>開始暫停</a>
                                                <a type="submit" class="btn btn-danger" disabled>結束暫停</a>
                                                <a type="submit" class="btn btn-success" disabled>完成</a>
                                                ';
                                            }
                                            ?>
                                            {!!$buttonHtm!!}
                                            
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                    <th scope="col">開始暫停</th>
                                                    <th scope="col">結束暫停</th>
                                                    <th scope="col">原因</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                <?php
                                                $record = array();
                                                $startrecords = $batch->Records()->where('state', 'starthold')->get();
                                                $eedrecords = $batch->Records()->where('state', 'endhold')->get();
                                                
                                                try {
                                                    for ($i=0; $i < sizeof($startrecords); $i++) { 
                                                        try{
                                                            $start_id = $startrecords[$i]->id;
                                                            $start = $startrecords[$i]->created_at;
                                                            $end = $eedrecords[$i]->created_at;
                                                            array_push($record, [
                                                                'id' => $start_id,
                                                                'start' => $start,
                                                                'end' => $end,
                                                                'note' => $startrecords[$i]->note,
                                                                'sum' => $start->diffInSeconds($end).'秒(約等於'.round((($start->diffInSeconds($end))/60), 2).'分鐘)',
                                                            ]);
                                                        }
                                                        catch (\Throwable $th) {
                                                            $start_id = $startrecords[$i]->id;
                                                            $start = $startrecords[$i]->created_at;
                                                            array_push($record, [
                                                                'id' => $start_id,
                                                                'start' => $start,
                                                                'end' => '--',
                                                                'note' => $startrecords[$i]->note,
                                                                'sum' => '--',
                                                            ]);
                                                        }
                                                    }
                                                } catch (\Throwable $th) {
                                                    
                                                }
                                                ?>

                                                @foreach ($record as $case)
                                                    <tr>
                                                        <th>{{$case['start']}}</th>
                                                        <th>{{$case['end']}}</th>
                                                        <th>
                                                        <form>
                                                            <div class="form-group">
                                                                <input type="text" name="records_id" class="form-control" value="{{$case['id']}}" style="display:none">
                                                                <select class="form-control" name="change_hold_reason" onchange="changeReason(this)">
                                                                    <option value="None" <?php if($case['note']=='None') echo 'selected';?> >無</option>
                                                                    <option value="機台PM" <?php if($case['note']=='機台PM') echo 'selected';?> >機台PM</option>
                                                                    <option value="機台異常" <?php if($case['note']=='機台異常') echo 'selected';?> >機台異常</option>
                                                                    <option value="用餐" <?php if($case['note']=='用餐') echo 'selected';?> >用餐</option>
                                                                    <option value="休息" <?php if($case['note']=='休息') echo 'selected';?> >休息</option>
                                                                </select>
                                                            </div>
                                                        </form>
                                                        </th>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            @elseif($_GET['show'] == 'all')
                                <div class="panel panel-default" {!!$background!!}>
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" 
                                            href="#collapse{{$batch->id}}">
                                            <p><span class="badge badge-secondary" style="background:<?php echo $Data['Color'][$batch->state]?>">{{$Data['States'][$batch->state]}}</span></p>
                                            <p>訂單建立時間：<?php
                                                try {
                                                    echo $batch->Runs->Order->created_at;
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>製程與產品: <?php
                                                try {
                                                    echo $batch->ProdProcessesList->Processes->process_code.$batch->ProdProcessesList->Products->product_name;
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>數量：<?php
                                                try {
                                                    echo $batch->quantity;
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>總時間：<?php
                                                try {
                                                    echo $batch->run_second.'秒(約等於'.round(($batch->run_second/60), 2).'分鐘)';
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>總休息：<?php
                                                try {
                                                    $startrecords = App\BatchStateRecord::where('batch_id', $batch->id)
                                                                                    ->where('state', 'starthold')->get();
                                                    $eedrecords = App\BatchStateRecord::where('batch_id', $batch->id)
                                                                                    ->where('state', 'endhold')->get();
                                                    $restSec = 0;
                                                    for ($i=0; $i < sizeof($startrecords); $i++) { 
                                                        $start = $startrecords[$i]->created_at;
                                                        $end = $eedrecords[$i]->created_at;
                                                        $restSec += $start->diffInSeconds($end);
                                                    }

                                                    echo $restSec.'秒(約等於'.round(($restSec/60), 2).'分鐘)';
                                                } catch (\Throwable $th) {
                                                    echo "--";
                                                }
                                            ?></p>
                                            <p>單位工時：<?php
                                                try {
                                                    $batch_id = $batch->id;
                                                    $pice = App\Batches::where('id', $batch_id)->first()->quantity;
                                                    $run_second = App\Batches::where('id', $batch_id)->first()->run_second;

                                                    $startrecords = App\BatchStateRecord::where('batch_id', $batch_id)
                                                                                    ->where('state', 'starthold')->get();
                                                    $eedrecords = App\BatchStateRecord::where('batch_id', $batch_id)
                                                                                    ->where('state', 'endhold')->get();
                                                    $restSec = 0;
                                                    for ($i=0; $i < sizeof($startrecords); $i++) { 
                                                        $start = $startrecords[$i]->created_at;
                                                        $end = $eedrecords[$i]->created_at;
                                                        $restSec += $start->diffInSeconds($end);
                                                    }

                                                    echo round(($pice/($run_second - $restSec)),2)  .'秒';
                                                } catch (\Throwable $th) {
                                                    echo "--";
                                                }
                                            ?></p>
                                            <p>標準工時：<?php
                                                try {
                                                    echo $batch->ProdProcessesList->process_time.'秒';
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>員工：<?php
                                                try {
                                                    echo $batch->Doer->name;
                                                } catch (\Throwable $th) {
                                                    echo '尚未指派';
                                                }
                                            ?></p>
                                            <p>加工編號：<?php
                                                try {
                                                    echo $batch->run_id;
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            <p>箱號：<?php
                                                try {
                                                    echo $batch->batch_code;
                                                } catch (\Throwable $th) {
                                                    echo '--';
                                                }
                                            ?></p>
                                            {{-- <p>訂單建立時間：{{$batch->Runs->Order->created_at}} </p>
                                            <p>製程與產品: {{$batch->ProdProcessesList->Processes->process_code}}{{$batch->ProdProcessesList->Products->product_name}}</p>
                                            <p>數量：{{$batch->quantity}}</p>
                                            <p>總時間：{{$batch->run_second.'秒(約等於'.round(($batch->run_second/60), 2).'分鐘)'}}</p>
                                            <p>總休息：<?php
                                                try {
                                                    $startrecords = App\BatchStateRecord::where('batch_id', $batch->id)
                                                                                    ->where('state', 'starthold')->get();
                                                    $eedrecords = App\BatchStateRecord::where('batch_id', $batch->id)
                                                                                    ->where('state', 'endhold')->get();
                                                    $restSec = 0;
                                                    for ($i=0; $i < sizeof($startrecords); $i++) { 
                                                        $start = $startrecords[$i]->created_at;
                                                        $end = $eedrecords[$i]->created_at;
                                                        $restSec += $start->diffInSeconds($end);
                                                    }

                                                    echo $restSec.'秒(約等於'.round(($restSec/60), 2).'分鐘)';
                                                } catch (\Throwable $th) {
                                                    echo "--";
                                                }
                                            ?></p>
                                            <p>單位工時：<?php
                                                try {
                                                    $batch_id = $batch->id;
                                                    $pice = App\Batches::where('id', $batch_id)->first()->quantity;
                                                    $run_second = App\Batches::where('id', $batch_id)->first()->run_second;

                                                    $startrecords = App\BatchStateRecord::where('batch_id', $batch_id)
                                                                                    ->where('state', 'starthold')->get();
                                                    $eedrecords = App\BatchStateRecord::where('batch_id', $batch_id)
                                                                                    ->where('state', 'endhold')->get();
                                                    $restSec = 0;
                                                    for ($i=0; $i < sizeof($startrecords); $i++) { 
                                                        $start = $startrecords[$i]->created_at;
                                                        $end = $eedrecords[$i]->created_at;
                                                        $restSec += $start->diffInSeconds($end);
                                                    }

                                                    echo round(($pice/($run_second - $restSec)),2)  .'秒';
                                                } catch (\Throwable $th) {
                                                    echo "--";
                                                }
                                            ?>
                                            </p>
                                            <p>標準工時：{{$batch->ProdProcessesList->process_time}}秒</p>
                                            <p>員工：<?php
                                                try {
                                                    echo $batch->Doer->name;
                                                } catch (\Throwable $th) {
                                                    echo '尚未指派';
                                                }
                                            ?></p>
                                            <p>加工編號：{{$batch->run_id}}</p>
                                            <p>箱號：{{$batch->batch_code}}</p> --}}
                                            </a>
                                        </h4>
                                    </div>
                                    @if ($batch->state == 'complete')
                                    <div id="collapse{{$batch->id}}" class="panel-collapse collapse out">
                                    @else
                                    <div id="collapse{{$batch->id}}" class="panel-collapse collapse in">
                                    @endif
                                        <div class="panel-body">
                                            <form>
                                                <input type="text" name="batchid" class="form-control" value="{{$batch->id}}" style="display:none" readonly>
                                                <div class="form-group">
                                                    <label for="order">製程順序</label>
                                                    <input type="text" name="order" class="form-control" value="{{$batch->ProdProcessesList->Processes->process_code}}" readonly>
                                                </div>
                                                <div class="form-group" style="display:none">
                                                    <label for="tool">機台</label>
                                                    <select class="form-control" name="tool">
                                                    <option value="TEST">測試機台</option>
                                                    {{-- <option value="Ng">未設定</option> --}}
                                                    {{-- @foreach ($Data['Tools'] as $tool)
                                                        @if ($tool->name == $batch->tool)
                                                            <option value="{{$tool->name}}" selected>{{$tool->name}}</option>
                                                        @else
                                                            <option value="{{$tool->name}}">{{$tool->name}}</option>
                                                        @endif
                                                    @endforeach --}}
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tool">員工</label>
                                                    <select class="form-control" name="doer_id">
                                                    <option value="Ng">未設定</option>
                                                    @foreach ($Data['Doers'] as $doer)
                                                        @if ($doer->id == Auth::user()->id)
                                                            <option value="{{$doer->id}}" selected>{{$doer->name}}</option>
                                                        @else
                                                            <option value="{{$doer->id}}">{{$doer->name}}</option>
                                                        @endif
                                                    @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="start_time">開始時間</label>
                                                    @if ($batch->start_time != '1000-01-01 00:00:00')
                                                        <input type="datetime" name="start_time" class="form-control" value="{{$batch->start_time}}" readonly>
                                                    @else
                                                        <input type="text" name="start_time" class="form-control" value="--" readonly>
                                                    @endif                                            
                                                </div>
                                                <div class="form-group">
                                                    <label for="end_time">結束時間</label>
                                                    @if ($batch->end_time != '1000-01-01 00:00:00')
                                                        <input type="datetime" name="end_time" class="form-control" value="{{$batch->end_time}}" readonly>
                                                    @else
                                                        <input type="text" name="end_time" class="form-control" value="--" readonly>
                                                    @endif    
                                                </div>
                                                <div class="form-group">
                                                    <label for="quantity">執行數量</label>
                                                    <input type="number" name="quantity" class="form-control" value="{{$batch->quantity}}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="scrap">報廢</label>
                                                    <input type="number" name="scrap" class="form-control" value="{{$batch->scrap}}" >
                                                </div>

                                                <div class="form-group" style="display:none">
                                                    <label for="hold_reason">暫停原因</label>
                                                    <select class="form-control" name="hold_reason">
                                                        <option value="None">無</option>
                                                        <option value="機台PM">機台PM</option>
                                                        <option value="機台異常">機台異常</option>
                                                        <option value="用餐">用餐</option>
                                                        <option value="休息">休息</option>
                                                    </select>
                                                </div>                                        
                                                <?php
                                                    $buttonHtm = '
                                                    <a type="submit" class="btn btn-primary btn-lg" onclick="process(this)">執行</a>
                                                    <a type="submit" class="btn btn-danger btn-lg" disabled>開始暫停</a>
                                                    <a type="submit" class="btn btn-danger btn-lg" disabled>結束暫停</a>
                                                    <a type="submit" class="btn btn-success btn-lg" onclick="complete(this)">完成</a>
                                                    ';
                                                    if ($batch->state == 'starthold') {
                                                        $buttonHtm = '
                                                        <a type="submit" class="btn btn-primary btn-lg" disabled>執行</a>
                                                        <a type="submit" class="btn btn-danger btn-lg" disabled>開始暫停</a>
                                                        <a type="submit" class="btn btn-danger btn-lg" onclick="endhold(this)">結束暫停</a>
                                                        <a type="submit" class="btn btn-success btn-lg" disabled>完成</a>
                                                        ';
                                                    }
                                                    else if($batch->state == 'endhold'){
                                                        $buttonHtm = '
                                                        <a type="submit" class="btn btn-primary btn-lg" onclick="process(this)">執行</a>
                                                        <a type="submit" class="btn btn-danger btn-lg" onclick="starthold(this)">開始暫停</a>
                                                        <a type="submit" class="btn btn-danger btn-lg" disabled>結束暫停</a>
                                                        <a type="submit" class="btn btn-success btn-lg" onclick="complete(this)">完成</a>
                                                        ';
                                                    }
                                                    else if($batch->state == 'process'){
                                                        $buttonHtm = '
                                                        <a type="submit" class="btn btn-primary btn-lg" disabled>執行</a>
                                                        <a type="submit" class="btn btn-danger btn-lg" onclick="starthold(this)">開始暫停</a>
                                                        <a type="submit" class="btn btn-danger btn-lg" disabled>結束暫停</a>
                                                        <a type="submit" class="btn btn-success btn-lg" onclick="complete(this)">完成</a>
                                                        ';
                                                    }
                                                    else if($batch->state == 'complete'){
                                                        $buttonHtm = '
                                                        <a type="submit" class="btn btn-primary btn-lg" disabled>執行</a>
                                                        <a type="submit" class="btn btn-danger btn-lg" disabled>開始暫停</a>
                                                        <a type="submit" class="btn btn-danger btn-lg" disabled>結束暫停</a>
                                                        <a type="submit" class="btn btn-success btn-lg" disabled>完成</a>
                                                        ';
                                                    }
                                                ?>
                                                {!!$buttonHtm!!}
                                                
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">開始暫停</th>
                                                        <th scope="col">結束暫停</th>
                                                        <th scope="col">原因</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    $record = array();
                                                    $startrecords = $batch->Records()->where('state', 'starthold')->get();
                                                    $eedrecords = $batch->Records()->where('state', 'endhold')->get();
                                                    
                                                    try {
                                                        for ($i=0; $i < sizeof($startrecords); $i++) { 

                                                            try{
                                                                $start_id = $startrecords[$i]->id;
                                                                $start = $startrecords[$i]->created_at;
                                                                $end = $eedrecords[$i]->created_at;
                                                                array_push($record, [
                                                                    'id' => $start_id,
                                                                    'start' => $start,
                                                                    'end' => $end,
                                                                    'note' => $startrecords[$i]->note,
                                                                    'sum' => $start->diffInSeconds($end).'秒(約等於'.round((($start->diffInSeconds($end))/60), 2).'分鐘)',
                                                                ]);
                                                            }
                                                            catch (\Throwable $th) {
                                                                $start_id = $startrecords[$i]->id;
                                                                $start = $startrecords[$i]->created_at;
                                                                array_push($record, [
                                                                    'id' => $start_id,
                                                                    'start' => $start,
                                                                    'end' => '--',
                                                                    'note' => $startrecords[$i]->note,
                                                                    'sum' => '--',
                                                                ]);
                                                            }
                                                        }
                                                    } catch (\Throwable $th) {
                                                        
                                                    }
                                                    ?>

                                                    @foreach ($record as $case)
                                                        <tr>
                                                            <th>{{$case['start']}}</th>
                                                            <th>{{$case['end']}}</th>
                                                            <th>
                                                            <form>
                                                                <div class="form-group">
                                                                    <input type="text" name="records_id" class="form-control" value="{{$case['id']}}" style="display:none">
                                                                    <select class="form-control" name="change_hold_reason" onchange="changeReason(this)">
                                                                        <option value="None" <?php if($case['note']=='None') echo 'selected';?> >無</option>
                                                                        <option value="機台PM" <?php if($case['note']=='機台PM') echo 'selected';?> >機台PM</option>
                                                                        <option value="機台異常" <?php if($case['note']=='機台異常') echo 'selected';?> >機台異常</option>
                                                                        <option value="用餐" <?php if($case['note']=='用餐') echo 'selected';?> >用餐</option>
                                                                        <option value="休息" <?php if($case['note']=='休息') echo 'selected';?> >休息</option>
                                                                    </select>
                                                                </div>
                                                            </form>
                                                            </th>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function process(event)
    {
        var batchId = $(event).closest('form').find('input[name="batchid"]').val();
        var scrap = $(event).closest('form').find('input[name="scrap"]').val();
        var tool = $(event).closest('form').find('select[name="tool"]').val();
        var doer = $(event).closest('form').find('select[name="doer_id"]').val();

        if(tool != 'Ng' || doer != 'Ng')
        {
            var jobCount = <?php echo $Data['JobCount']?>;
            
            if(jobCount == 0)
            {
                $.ajax({
                    type: "GET",
                    url: "/ajax/process_start",
                    dataType: "json",
                    data:{
                        batchId: batchId,
                        doer_id: doer,
                        tool: tool,
                    },
                    success: function (response) {
                        if(response == 'ok')
                        {
                            alert('開始執行');
                        }
                        else
                        {
                            alert('您無法執行此操作');
                        }
                        location.reload();
                    },
                    error: function (thrownError) {
                        console.log(thrownError);
                    }
                });
            }
            else
            {
                alert("尚有"+jobCount+"筆工作未完成，請先完成!");
            }
            
        }
        else
        {
            alert('未設定機台或員工');
        }
        
    }

    function starthold(event)
    {
        var batchId = $(event).closest('form').find('input[name="batchid"]').val();
        var scrap = $(event).closest('form').find('input[name="scrap"]').val();
        var tool = $(event).closest('form').find('select[name="tool"]').val();
        var doer = $(event).closest('form').find('select[name="doer_id"]').val();
        var holdReason = $(event).closest('form').find('select[name="hold_reason"]').val();

        $.ajax({
            type: "GET",
            url: "/ajax/process_starthold",
            dataType: "json",
            data:{
                batchId: batchId,
                doer_id: doer,
                tool: tool,
                holdReason: holdReason
            },
            success: function (response) {
                alert('開始暫停');
                location.reload();
            },
            error: function (thrownError) {
                console.log(thrownError);
            }
        });
    }

    function endhold(event)
    {
        var batchId = $(event).closest('form').find('input[name="batchid"]').val();
        var scrap = $(event).closest('form').find('input[name="scrap"]').val();
        var tool = $(event).closest('form').find('select[name="tool"]').val();
        var doer = $(event).closest('form').find('select[name="doer_id"]').val();
        var holdReason = $(event).closest('form').find('select[name="hold_reason"]').val();

        if(tool != 'Ng' || doer != 'Ng')
        {
            $.ajax({
                type: "GET",
                url: "/ajax/process_endhold",
                dataType: "json",
                data:{
                    batchId: batchId,
                    doer_id: doer,
                    tool: tool,
                    holdReason: holdReason
                },
                success: function (response) {
                    alert('結束暫停並開始執行');
                    location.reload();
                },
                error: function (thrownError) {
                    console.log(thrownError);
                }
            });
        }
        else
        {
            alert('未設定機台或員工');
        }
        
    }

    function complete(event)
    {
        var batchId = $(event).closest('form').find('input[name="batchid"]').val();
        var scrap = $(event).closest('form').find('input[name="scrap"]').val();
        var tool = $(event).closest('form').find('select[name="tool"]').val();
        var doer = $(event).closest('form').find('select[name="doer_id"]').val();

        $.ajax({
            type: "GET",
            url: "/ajax/process_complete",
            dataType: "json",
            data:{
                batchId: batchId,
                doer_id: doer,
                tool: tool,
            },
            success: function (response) {
                alert('完成');
                location.reload();
            },
            error: function (thrownError) {
                console.log(thrownError);
            }
        });
    }

    function changeReason(event)
    {
        var records_id = $(event).closest('form').find('input[name="records_id"]').val();
        var change_hold_reason = $(event).closest('form').find('select[name="change_hold_reason"]').val();
        $.ajax({
            type: "GET",
            url: "/ajax/change_hold_reason",
            dataType: "json",
            data:{
                records_id: records_id,
                change_hold_reason: change_hold_reason,
            },
            success: function (response) {
                console.log(response);
            },
            error: function (thrownError) {
                console.log(thrownError);
            }
        });
    }
    
</script>

@endsection
