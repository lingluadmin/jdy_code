<div class="Js_tab_box t-center-left-4">
    <ul class="Js_tab t-center-tab tab-two">
        <li class="cur">项目详情<span></span></li>
        {{-- <li>安全保障<span></span></li> --}}
        <li class="t-brn">还款计划<span></span></li>
    </ul>
    <div class="js_tab_content">
        <!--项目描述-->
        <div class="Js_tab_main t-center-left-5" style="display:block;">

            <!-- 保理债权 -->
            <table class="detail-table">
                <tr>
                    <th width="20%">项目名称</th>
                    <td>{{$project['product_line_note']}} {{$project['invest_time_note']}}  {{$project['id']}}</td>
                </tr>
                <tr>
                    <th>借款利率</th>
                    <td>{{(float)$project['profit_percentage']}}%</td>
                </tr>
                <tr>
                    <th>借款期限</th>
                    <td>{{ $project['format_invest_time'] . $project['invest_time_unit']}}</td>
                </tr>
                <tr>
                    <th>还款方式</th>
                    <td>{{$project['refund_type_note']}}</td>
                </tr>
                <tr>
                    <th>到期还款日</th>
                    <td>{{$project['end_at']}}</td>
                </tr>
                <tr>
                    <th>借款总额</th>
                    <td>{{ $project['total_amount'] }}元</td>
                </tr>
                <tr>
                    <th>募集开始时间</th>
                    <td>{{ date('Y-m-d',\App\Tools\ToolTime::getUnixTime($project['publish_at'])) }}（募集时间最长不超过20天）</td>
                </tr>
                <tr>
                    <th>风险等级</th>
                    <td>保守型</td>
                </tr>
                <tr>
                    <th>出借条件</th>
                    <td>最低100元起投，最高不超过剩余项目总额</td>
                </tr>
                <tr>
                    <th>提前赎回方式</th>
                    <td>持有债权项目30天（含）即可申请债权转让，赎回时间以实际转让成功时间为准</td>
                </tr>
                <tr>
                    <th>费用</th>
                    <td>买入费用：0.00%<br>退出费用：0.00%<br>提前赎回费率：0.00%</td>
                </tr>
                <tr>
                    <th>项目介绍</th>
                    <td>{{isset($company['factor_summarize']) ? htmlspecialchars_decode($company['factor_summarize']) : '九安心产品是保理公司将应收账款收益权转让给出借人；原债权企业多为国企及上市公司，切负有连带责任，借款期限一般为30~90天，适合偏好短期，且稳定的出借人'}}。</td>
                </tr>
                {{--<tr>
                    <th>协议范本</th>
                    <td><a href="javascript:;" class="blue" data-target="moduldetail">【点击查看】</a></td>
                </tr>--}}
            </table>
            <dl class="detail-info-style1">
                <dt>债权企业信息</dt>
                <dd>
                    <p><span>债权企业名称：{{isset($company['credit_company']) ? $company['credit_company'] : null}}</span><span>企业证件号：{{isset($company['format_loan_user_identity']) && !empty($company['format_loan_user_identity']) ? substr($company['format_loan_user_identity'][0] ,0,4) .'******' : null}}</span></p>
                    <p>{{isset($company['family_register']) ? '<span>经营地址：' . $company['family_register'] . '</span>' : null}}</span><span>借款用途：资金周转</span></p>
                </dd>
            </dl>
            <!-- End 保理债权 -->
        </div>

        <!--风险控制-->
        {{-- <div class="Js_tab_main t-center-left-7" style="display:none;">
            <dl class="t-center-left-6">
                <dt><span></span>风险控制</dt>
                <dd>平台对每个投资项目都有相应保障措施，同时建立了风险准备金账户，对平台每个投资项目提取 1%作为风险准备金。</dd>
            </dl>
            <div class="t-online"></div>
            <dl class="t-center-left-6">
                <dt><span></span>资金安全</dt>
                <dd>
                    <p>1.九斗鱼记录出借人的每笔投资，并生成符合法律法规的有效合同文件，且所有的
资金流向均由独立第三方机构代为管理，以确保用户资金安全；</p>
                    <p>2.九斗鱼平台的所有投资项目均通过多重风险控制审核，并对投资项目进行全面风
险管理，以最大程度保障出借人的资金安全；</p>
                    <p>3.九斗鱼平台全程采用 VeriSign256 位 SSL 强制加密证书进行数据加密传输，有效
保障银行账号、交易密码等机密信息在网络传输过程中不被查看、修改或窃取。</p>
                    <p>4.平台所有的投资项目均交纳 1%作为风险准备金，由东亚银行监管；查看<a href="/content/article/reservefund?id=815">《风险准备金账户》</a></p>
                </dd>
            </dl>
        </div> --}}

        <!-- 引入还款计划 -->
        @include('pc.invest.project.newDetail.refundPlan')
    </div>
</div>
