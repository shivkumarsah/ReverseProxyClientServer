@extends('layouts.layout')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"  ng-controller="dashboardCtrl">
    <h1 class="page-header">{{trans('messages.dashboard.page_title')}}</h1>
    <div id="viewPanel">
          <div class="row set-margin">
              <div class="col-md-3">
                  <div class="control-report report-student-teacher-color">
                     
                      <div class="control-report-child-1">[[graphData.studentCount]]</div>
                      <div class="control-report-child-2" ng-show="showCount">Students</div>
                  </div>
              </div>

              <div class="col-md-3">
                  <div class="control-report report-teacher-school-color">
                    
                     <div class="control-report-child-1">[[graphData.teacherCount]]</div>
                     <div class="control-report-child-2" ng-show="showCount">Teachers</div>
                  </div>
              </div>

              <div class="col-md-3">
                  <div class="control-report report-student-school-color">
                    
                     <div class="control-report-child-1">[[graphData.courseCount]]</div>
                     <div class="control-report-child-2" ng-show="showCount">Courses</div>
                  </div>
              </div>

              <div class="col-md-3">
                  <div class="control-report report-course-school-color">
                     
                     <div class="control-report-child-1">[[graphData.schoolCount]]</div>
                     <div class="control-report-child-2" ng-show="showCount">Schools</div>
                  </div>
              </div>
          </div>

        <div class="row set-margin">
            <div class="col-md-6 col-sm-6">
           <!-- BEGIN PORTLET-->
               <div class="portlet light ">
                   <div class="portlet-title">
                       <div class="caption">
                           <i class="icon-share font-red-sunglo hide"></i>
                           <span class="caption-subject font-chart-header">Hits per developer</span>
                           <span class="caption-helper"></span>
                       </div>

                       <div class="actions">
                           <div class="btn-group">
                               <!--<a href="" class="btn grey-salsa btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                               Filter Range&nbsp;<span class="fa fa-angle-down">
                               </span>
                               </a>
                               <ul class="dropdown-menu pull-right" style="min-width: 120px;">
                                   <li>
                                       <a href="javascript:;">Developer 1</a>
                                   </li>
                                   <li>
                                       <a href="javascript:;">Developer 2</a>
                                   </li>
                                   <li class="active">
                                       <a href="javascript:;">Developer 3</a>
                                   </li>
                                   <li>
                                       <a href="javascript:;">Developer 4</a>
                                   </li>
                               </ul>-->
                           </div>
                       </div>
                   </div>
                   <div class="portlet-body">
                        <div id="site_statistics_loading">
                           <!--<img src="http://www.keenthemes.com/preview/metronic/theme/assets/admin/layout2/img/loading.gif" alt="loading"/>-->
                        </div>
                       <div id="site_activities_content" class="display-none">
                           <div id="site_activities" style="height: 300px;">
                           </div>
                       </div>
                   </div>
               </div>
           <!-- END PORTLET-->
            </div>
            <div class="col-md-6 col-sm-6">
               <!-- BEGIN BASIC CHART PORTLET-->
                    <div class="portlet box">
                        <div class="portlet-title">
                            <div class="caption caption-title">
                                <i class="icon-bar-chart font-green-sharp hide"></i>
                                <span class="caption-subject font-chart-header">Last Imported Data</span>
                                <span class="caption-helper"></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div id="chart_1_1_legendPlaceholder">
                            </div>
                            <div id="chart_1_1" class="chart">
                            </div>
                        </div>
                    </div>
               <!-- END BASIC CHART PORTLET-->
            </div>
        </div>

        <div class="row set-margin">
            <div class="col-md-6">
                <div class="dashboard-stat blue-madison">
                    <div class="visual">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="details">
                        <div class="number">[[graphData.developerCount]]</div>
                        <div class="desc">Total number of developers</div>
                    </div>
                    <div class="more" href="#" style="text-align: right;"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-stat red-intense">
                    <div class="visual">
                        <i class="fa fa-cogs"></i>
                    </div>
                    <div class="details">
                        <div class="number">[[graphData.apiLogCountToday]]</div>
                        <div class="desc">Total API Calls Today</div>
                    </div>
                    <div class="more" href="#" style="text-align: right;">TOTAL NUMBER OF API CALLS [[graphData.apiLogCount]]</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
