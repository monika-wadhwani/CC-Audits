<style>
    #loaderBox {
        align-items: center;
    }

    .sideBar {
        overflow-y: auto;
        overflow-x: visible;
    }

    .sideBar::-webkit-scrollbar {
        width: 4px;
    }

    /* Track */
    .sideBar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    .sideBar::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    .sideBar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<div class="sideBar">
    <figure class="m-0 text-left mb-3 position-sticky top-0 z-1" style="background-color: #2962ff;">
        <img src="/assets/design/img/porter-logo-v3-blue.svg" class="logoPortal ms-3" alt="logo" width="130" height="55">
    </figure>
    <ul class="dashboardIcon ps-3" id="leftMenuUl">
        @if (Auth::user()->hasRole('agent') || Auth::user()->hasRole('agent-tl'))
            <!-- <li>
                <a href="/home" class="{{ Request::segment(1) === 'home' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/Iconmaterial-dashboard.svg" alt="dashboardicon"
                        width="18" height="20">
                    <span class="d-block">Dashboard</span>
                </a>
            </li> -->
            <li>
                <a href="/agent_dashboard2" class="{{ Request::segment(1) === 'agent_dashboard2' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/Iconmaterial-dashboard.svg" alt="dashboardicon"
                        width="18" height="20">
                    <span class="d-block">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="/agent_feedback"
                    class="{{ Request::segment(1) === 'agent_feedback' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/feedback.svg" alt="dashboardicon" width="18"
                        height="20">
                    <span class="d-block">Feedback</span>
                </a>
            </li>
            <li>
                <a href="/report/rebuttal_status_report"
                    class="{{ Request::segment(2) === 'rebuttal_status_report' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/Audits.svg" alt="dashboardicon" width="18"
                        height="20">
                    <span class="d-block">Rebuttal</span>
                </a>
            </li>
            {{-- <li>
                <a href="/report/monthly_trending_report" class="loaderLoad">
                    <img src="/assets/design/img/rebuttal.svg" class="loaderLoad" alt="dashboardicon" width="18"
                        height="20">
                    <span class="d-block">Monthly Trending Report</span>
                </a>
            </li> --}}
        @elseif(Auth::user()->hasRole('qa'))
        @if(Auth::user()->assigned_sheet_id == 137)
            <li>
                <a href="/qa_dashboard2" class="{{ Request::segment(1) === 'qa_dashboard2' ? 'active' : null }} loaderLoad">
                    <img src="/assets/design/img/Iconmaterial-dashboard.svg" alt="dashboardicon" width="18"
                        height="20" class="loaderLoad">
                    <span class="d-block">Dashboard-2</span>
                </a>
            </li>
        @else
        <li>
            <a href="/home" class="{{ Request::segment(1) === 'home' ? 'active' : null }} loaderLoad">
                <img src="/assets/design/img/Iconmaterial-dashboard.svg" alt="dashboardicon" width="18"
                    height="20" class="loaderLoad">
                <span class="d-block">Dashboard</span>
            </a>
        </li>
        @endif
            <li>
                <a href="/audit_sheet/{{ Crypt::encrypt(Auth::user()->assigned_sheet_id) }}"
                    class="{{ Request::segment(1) === 'audit_sheet' ? 'active' : null }}  loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/feedback.svg" alt="dashboardicon" width="18"
                        height="20">
                    <span class="d-block">Audit-Sheet</span>
                </a>
            </li>
            <li>
                <a href="/audited_list/"
                    class="{{ Request::segment(1) === 'audited_list' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="18"
                        height="20">
                    <span class="d-block">Main Pool</span>
                </a>
            </li>
            <li>
                <a href="/tmp_audited_list/{{ Crypt::encrypt(Auth::user()->assigned_sheet_id) }}"
                    class="{{ Request::segment(1) === 'tmp_audited_list' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="18"
                        height="20">
                    <span class="d-block">Temp Pool</span>
                </a>
            </li>

            {{-- <li>
                <a href="/raised_rebuttal_list"
                    class="{{ Request::segment(1) === 'raised_rebuttal_list' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="18"
                        height="20">
                    <span class="d-block">My Rebuttals</span>
                </a>
            </li> --}}
         
            @endif
        @if(Auth::user()->hasRole('qtl') || Auth::user()->hasRole('mis') || Auth::user()->partner_id == 99)
        @if(Auth::user()->id != 333)
        <li>			
            <a href="/home" class="{{ Request::segment(1) === 'profile' ? 'active' : null }}">
                <img src="/assets/design/img/Profile.svg" class="loaderLoad" alt="dashboardicon" width="18"
                    height="20">
                <span class="d-block">Dashboard</span>
            </a>
        </li>
        <li>
            <a data-bs-toggle="collapse" href="#userCollapse" data-bs-parent="#leftMenuUl" 
                class="{{ Request::segment(1) === 'create' ? 'active' : null }} d-flex">
                <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                    height="20">
                <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                <span class="">User</span>
                <i class="bi bi-chevron-down arrow"></i>
                <!-- </div> -->
            </a>
        </li>
        <div id="userCollapse" class="collapse" data-bs-parent="#leftMenuUl">
            <ul>
                <li>
                    <a href="/user/create"
                        class="{{ Request::segment(1) === 'create' ? 'active' : null }} loaderLoad">
                        <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                        <span class="ms-0 submenu">User Create</span>
                    </a>
                </li>
                <li>
                    <a href="/user" class="{{ Request::segment(1) === 'user' ? 'active' : null }} loaderLoad">
                        <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                        <span class="ms-0 submenu">User Manage</span>
                    </a>
                </li>
            </ul>
        </div>
        <li>
            <a href="/scenerio_tree"
                class="{{ Request::segment(1) === 'scenerio_tree' ? 'active' : null }} loaderLoad d-flex">
                <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                    height="20">
                <span class="">Scenario Code</span>
            </a>
        </li>
        <li>
            <a data-bs-toggle="collapse" href="#error_code"
                class="{{ Request::segment(1) === 'error_code/dump_uploader' ? 'active' : null }} d-flex">
                <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                    height="20">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <span class="">Error Code & Reason Type</span>
                    <i class="bi bi-chevron-down arrow"></i>
                </div>
            </a>
        </li>
        <div id="error_code" class="collapse" data-bs-parent="#leftMenuUl">
            <ul>
                <li>
                    <a href="/error_code/dump_uploader"
                        class="{{ Request::segment(1) === 'error_code/dump_uploader' ? 'active' : null }} loaderLoad">
                        <span class="">Error Code Dump Upload</span>
                    </a>
                </li>
                <li>
                    <a href="/error_codes_list"
                        class="{{ Request::segment(1) === 'error_codes_list' ? 'active' : null }} loaderLoad">
                        <span class="">List</span>
                    </a>
                </li>

            </ul>
        </div>
        <li>
            <a data-bs-toggle="collapse" href="#role_mapping"
                class="{{ Request::segment(1) === 'role_mapping/list' ? 'active' : null }} d-flex">
                <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                    height="20">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <span class="">Cluster Role Mapping</span>
                    <i class="bi bi-chevron-down arrow"></i>
                </div>
            </a>
        </li>
        <div id="role_mapping" class="collapse" data-bs-parent="#leftMenuUl">
            <ul>
                <li>
                    <a href="/role_mapping/list"
                        class="{{ Request::segment(1) === 'role_mapping/list' ? 'active' : null }} loaderLoad">
                        <span class="">Mapping</span>
                    </a>
                </li>
                <li>
                    <a href="/qa_allocation"
                        class="{{ Request::segment(1) === 'qa_allocation' ? 'active' : null }} loaderLoad">
                        <span class="">Allocation To Auditors</span>
                    </a>
                </li>
            </ul>
        </div>
        <li>
            <a data-bs-toggle="collapse" href="#audit_cycle" class="d-flex"
                class="{{ Request::segment(1) === 'audit_cycle' ? 'active' : null }}">
                <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                    height="20">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <span class="">Audit Cycle</span>
                    <i class="bi bi-chevron-down arrow"></i>
                </div>
            </a>
        </li>
        <div id="audit_cycle" class="collapse" data-bs-parent="#leftMenuUl">
            <ul>
                <li>
                    <a href="/audit_cycle/create"
                        class="{{ Request::segment(1) === '/audit_cycle/create' ? 'active' : null }} loaderLoad">
                        <span class="">Create</span>
                    </a>
                </li>
                <li>
                    <a href="/audit_cycle"
                        class="{{ Request::segment(1) === '/audit_cycle' ? 'active' : null }} loaderLoad">
                        <span class="">Manage</span>
                    </a>
                </li>

            </ul>
        </div>
        <li>
            <a data-bs-toggle="collapse" href="#process" data-bs-parent="#leftMenuUl" class="d-flex"
                class="{{ Request::segment(1) === 'create' ? 'active' : null }}">
                <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                    height="20">
                <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                <span class="">Process</span>
                <i class="bi bi-chevron-down arrow"></i>
                <!-- </div> -->
            </a>
        </li>
        <div id="process" class="collapse" data-bs-parent="#leftMenuUl">
            <ul>
                <li>
                    <a href="/process/create"
                        class="{{ Request::segment(1) === 'process/create' ? 'active' : null }} loaderLoad">
                        <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                        <span class="ms-0 submenu">Create</span>
                    </a>
                </li>
                <li>
                    <a href="/process"
                        class="{{ Request::segment(1) === '/process' ? 'active' : null }} loaderLoad">
                        <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                        <span class="ms-0 submenu">Manage</span>
                    </a>
                </li>
            </ul>
        </div>
        <li>
            <a data-bs-toggle="collapse" href="#region" data-bs-parent="#leftMenuUl" class="d-flex"
                class="{{ Request::segment(1) === 'create' ? 'active' : null }}">
                <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                    height="20">
                <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                <span class="">Region</span>
                <i class="bi bi-chevron-down arrow"></i>
                <!-- </div> -->
            </a>
        </li>
        <div id="region" class="collapse" data-bs-parent="#leftMenuUl">
            <ul>
                <li>
                    <a href="/region/create"
                        class="{{ Request::segment(1) === '/region/create' ? 'active' : null }} loaderLoad">
                        <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                        <span class="ms-0 submenu">Create</span>
                    </a>
                </li>
                <li>
                    <a href="/region"
                        class="{{ Request::segment(1) === '/region' ? 'active' : null }} loaderLoad">
                        <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                        <span class="ms-0 submenu">Manage</span>
                    </a>
                </li>
            </ul>
        </div>
        <li>
            <a data-bs-toggle="collapse" href="#language" data-bs-parent="#leftMenuUl" class="d-flex"
                class="{{ Request::segment(1) === 'create' ? 'active' : null }}">
                <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                    height="20">
                <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                <span class="">Language</span>
                <i class="bi bi-chevron-down arrow"></i>
                <!-- </div> -->
            </a>
        </li>
        <div id="language" class="collapse" data-bs-parent="#leftMenuUl">
            <ul>
                <li>
                    <a href="/language/create"
                        class="{{ Request::segment(1) === '/language/create' ? 'active' : null }} loaderLoad">
                        <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                        <span class="ms-0 submenu">Create</span>
                    </a>
                </li>
                <li>
                    <a href="/language"
                        class="{{ Request::segment(1) === '/language' ? 'active' : null }} loaderLoad">
                        <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                        <span class="ms-0 submenu">Manage</span>
                    </a>
                </li>
            </ul>
        </div>
        <li>
            <a data-bs-toggle="collapse" href="#allocation" data-bs-parent="#leftMenuUl" class="d-flex"
                class="{{ Request::segment(1) === 'Client' ? 'active' : null }}">
                <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                    height="20">
                <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                <span class="">Allocation</span>
                <i class="bi bi-chevron-down arrow"></i>
                <!-- </div> -->
            </a>
        </li>
        <div id="allocation" class="collapse" data-bs-parent="#leftMenuUl">
            <ul>
                <li>
                    <a href="/allocation/qtl_qa"
                        class="{{ Request::segment(1) === 'allocation/qtl_qa' ? 'active' : null }} loaderLoad">
                        <span class="">QTL -> QA</span>
                    </a>
                </li>
                <li>
                    <a href="/allocation/qa_sheet"
                        class="{{ Request::segment(1) === 'allocation/qa_sheet' ? 'active' : null }} loaderLoad">
                        <span class="">QA -> QM-Sheet</span>
                    </a>
                </li>
                <li>
                    <a href="/allocation/qa_target"
                        class="{{ Request::segment(1) === 'allocation/qa_target' ? 'active' : null }} loaderLoad">
                        <span class="">Set QA Target</span>
                    </a>
                </li>
                <li>
                    <a href="/allocation/dump_uploader"
                        class="{{ Request::segment(1) === 'allocation/dump_uploader' ? 'active' : null }} loaderLoad">
                        <span class="">Agents / Call Dump Uploader</span>
                    </a>
                </li>
            </ul>
        </div>
        <li>
                <a data-bs-toggle="collapse" href="#reports" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'report' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Reports</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>

            <div id="reports" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/report/raw_dump_with_audit_report"
                            class="{{ Request::segment(1) === 'report/raw_dump_with_audit_report' ? 'active' : null }} loaderLoad">
                            <span class="">Raw Dump with Audit Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="/report/rebuttal_status_report"
                            class="{{ Request::segment(1) === 'report/rebuttal_status_report' ? 'active' : null }} loaderLoad">
                            <span class="">Rebuttal Report</span>
                        </a>
                    </li>
                </ul>
            </div>
    
        @endif
        @endif
        @if(Auth::user()->hasRole('admin'))
            <div id="userCollapse" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/user/create"
                            class="{{ Request::segment(1) === 'create' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">User Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/user" class="{{ Request::segment(1) === 'user' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">User Manage</span>
                        </a>
                    </li>
                    <li>
                        <a href="/questions_upload" class="{{ Request::segment(1) === 'user' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">Scanerio Questions and Answers Upload</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
        
        @if(Auth::user()->hasRole('qtl') && Auth::user()->id == 663 || Auth::user()->partner_id == 98)
        <li>
            <a href="/qtl_dashboard2" class="{{ Request::segment(1) === 'qtl_dashboard2' ? 'active' : null }} loaderLoad">
                <img class="loaderLoad" src="/assets/design/img/Iconmaterial-dashboard.svg" alt="dashboardicon"
                    width="18" height="20">
                <span class="d-block">Dashboard-2</span>
            </a>
        </li>
            <li>
                <a data-bs-toggle="collapse" href="#userCollapse" data-bs-parent="#leftMenuUl" 
                    class="{{ Request::segment(1) === 'create' ? 'active' : null }} d-flex">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">User</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="userCollapse" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/user/create"
                            class="{{ Request::segment(1) === 'create' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">User Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/user" class="{{ Request::segment(1) === 'user' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">User Manage</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#targetCollapse"
                    class="{{ Request::segment(1) === 'qa_target/set' ? 'active' : null }} d-flex">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="">QA Target</span>
                        <i class="bi bi-chevron-down arrow"></i>
                    </div>
                </a>
            </li>
            <div id="targetCollapse" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/qa_target/set"
                            class="{{ Request::segment(1) === 'qa_target/set' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">Set QA Target</span>
                        </a>
                    </li>
                    <li>
                        <a href="/qa_target/list"
                            class="{{ Request::segment(1) === 'qa_target/list' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0">Manage</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a href="/scenerio_tree"
                    class="{{ Request::segment(1) === 'scenerio_tree' ? 'active' : null }} loaderLoad d-flex">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <span class="">Scenario Code</span>
                </a>
            </li>


            <li>
                <a data-bs-toggle="collapse" href="#error_code"
                    class="{{ Request::segment(1) === 'error_code/dump_uploader' ? 'active' : null }} d-flex">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="">Error Code & Reason Type</span>
                        <i class="bi bi-chevron-down arrow"></i>
                    </div>
                </a>
            </li>
            <div id="error_code" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/error_code/dump_uploader"
                            class="{{ Request::segment(1) === 'error_code/dump_uploader' ? 'active' : null }} loaderLoad">
                            <span class="">Error Code Dump Upload</span>
                        </a>
                    </li>
                    <li>
                        <a href="/error_codes_list"
                            class="{{ Request::segment(1) === 'error_codes_list' ? 'active' : null }} loaderLoad">
                            <span class="">List</span>
                        </a>
                    </li>

                </ul>
            </div>

            <li>
                <a data-bs-toggle="collapse" href="#agent_feedback_email"
                    class="{{ Request::segment(1) === 'agent_feedback_email/set' ? 'active' : null }} d-flex">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="">Agent Feedback Email</span>
                        <i class="bi bi-chevron-down arrow"></i>
                    </div>
                </a>
            </li>
            <div id="agent_feedback_email" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/agent_feedback_email/set"
                            class="{{ Request::segment(1) === 'agent_feedback_email/set' ? 'active' : null }} loaderLoad">
                            <span class="">Add Email</span>
                        </a>
                    </li>
                    <li>
                        <a href="/agent_feedback_email/list"
                            class="{{ Request::segment(1) === 'agent_feedback_email/list' ? 'active' : null }} loaderLoad">
                            <span class="">Manage Email</span>
                        </a>
                    </li>

                </ul>
            </div>
            <li>
                <a href="/raised_rebuttal_list"
                    class="{{ Request::segment(1) === 'raised_rebuttal_list' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <span class="">My Rebuttal</span>
                </a>
            </li>

            <li>
                <a data-bs-toggle="collapse" href="#role_mapping"
                    class="{{ Request::segment(1) === 'role_mapping/list' ? 'active' : null }} d-flex">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="">Cluster Role Mapping</span>
                        <i class="bi bi-chevron-down arrow"></i>
                    </div>
                </a>
            </li>
            <div id="role_mapping" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/role_mapping/list"
                            class="{{ Request::segment(1) === 'role_mapping/list' ? 'active' : null }} loaderLoad">
                            <span class="">Mapping</span>
                        </a>
                    </li>
                    <li>
                        <a href="/qa_allocation"
                            class="{{ Request::segment(1) === 'qa_allocation' ? 'active' : null }} loaderLoad">
                            <span class="">Allocation To Auditors</span>
                        </a>
                    </li>
                </ul>
            </div>

            <li>
                <a data-bs-toggle="collapse" href="#randomizer_report"
                    class="{{ Request::segment(1) === 'randomizer_report' ? 'active' : null }} d-flex">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="">Randomizer</span>
                        <i class="bi bi-chevron-down arrow"></i>
                    </div>
                </a>
            </li>
            <div id="randomizer_report" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/randomizer_report"
                            class="{{ Request::segment(1) === 'qa_allocation' ? 'active' : null }} loaderLoad">
                            <span class="">Randomizer Samples List</span>
                        </a>
                    </li>

                    <li>
                        <a href="/set" class="{{ Request::segment(1) === 'set' ? 'active' : null }} loaderLoad">
                            <span class="">Set Randomizer Sample Rules</span>
                        </a>
                    </li>
                    <li>
                        <a href="/qa_allocation"
                            class="{{ Request::segment(1) === 'qa_allocation' ? 'active' : null }} loaderLoad">
                            <span class="">Allocation To Auditors</span>
                        </a>
                    </li>
                </ul>
            </div>


            <li>
                <a data-bs-toggle="collapse" href="#qa_daily_target" class="d-flex"
                    class="{{ Request::segment(1) === 'qa_daily_target' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="">QA Daily Target</span>
                        <i class="bi bi-chevron-down arrow"></i>
                    </div>
                </a>
            </li>
            <div id="qa_daily_target" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/qa_daily_target/set"
                            class="{{ Request::segment(1) === 'qa_daily_target/set' ? 'active' : null }} loaderLoad">
                            <span class="">Set QA Daily Target</span>
                        </a>
                    </li>
                    <li>
                        <a href="/qa_daily_target/list"
                            class="{{ Request::segment(1) === 'qa_daily_target/list' ? 'active' : null }} loaderLoad">
                            <span class="">Manage</span>
                        </a>
                    </li>

                </ul>
            </div>

            <li>
                <a data-bs-toggle="collapse" href="#trainning_pkt" class="d-flex"
                    class="{{ Request::segment(1) === 'trainning_pkt' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="">Training PKT</span>
                        <i class="bi bi-chevron-down arrow"></i>
                    </div>
                </a>
            </li>
            <div id="trainning_pkt" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/trainning_pkt/set"
                            class="{{ Request::segment(1) === 'trainning_pkt/set' ? 'active' : null }} loaderLoad">
                            <span class="">Upload Training PKT</span>
                        </a>
                    </li>
                    <li>
                        <a href="/trainning_pkt/list"
                            class="{{ Request::segment(1) === 'trainning_pkt/list' ? 'active' : null }} loaderLoad">
                            <span class="">Manage</span>
                        </a>
                    </li>

                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#audit_alert_box" class="d-flex"
                    class="{{ Request::segment(1) === 'audit_alert_box' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="">Audit Alert Box</span>
                        <i class="bi bi-chevron-down arrow"></i>
                    </div>
                </a>
            </li>

            <div id="audit_alert_box" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/audit_alert_box/create"
                            class="{{ Request::segment(1) === 'audit_alert_box/create' ? 'active' : null }} loaderLoad">
                            <span class="">Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/audit_alert_box"
                            class="{{ Request::segment(1) === 'audit_alert_box' ? 'active' : null }} loaderLoad">
                            <span class="">Manage</span>
                        </a>
                    </li>

                </ul>
            </div>

            <li>
                <a data-bs-toggle="collapse" href="#audit_cycle" class="d-flex"
                    class="{{ Request::segment(1) === 'audit_cycle' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="">Audit Cycle</span>
                        <i class="bi bi-chevron-down arrow"></i>
                    </div>
                </a>
            </li>
            <div id="audit_cycle" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/audit_cycle/create"
                            class="{{ Request::segment(1) === '/audit_cycle/create' ? 'active' : null }} loaderLoad">
                            <span class="">Set QA Daily Target</span>
                        </a>
                    </li>
                    <li>
                        <a href="/audit_cycle"
                            class="{{ Request::segment(1) === '/audit_cycle' ? 'active' : null }} loaderLoad">
                            <span class="">Manage</span>
                        </a>
                    </li>

                </ul>
            </div>


            <li>
                <a data-bs-toggle="collapse" href="#process" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'create' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Process</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="process" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/process/create"
                            class="{{ Request::segment(1) === 'process/create' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/process"
                            class="{{ Request::segment(1) === '/process' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">Manage</span>
                        </a>
                    </li>
                </ul>
            </div>

            <li>
                <a data-bs-toggle="collapse" href="#region" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'create' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Region</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="region" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/region/create"
                            class="{{ Request::segment(1) === '/region/create' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/region"
                            class="{{ Request::segment(1) === '/region' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">Manage</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#language" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'create' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Language</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="language" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/language/create"
                            class="{{ Request::segment(1) === '/language/create' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/language"
                            class="{{ Request::segment(1) === '/language' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">Manage</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#reason" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'create' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Reason</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="reason" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/reason/create"
                            class="{{ Request::segment(1) === '/reason/create' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/reason"
                            class="{{ Request::segment(1) === '/reason' ? 'active' : null }} loaderLoad">
                            <!-- <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21" height="20"> -->
                            <span class="ms-0 submenu">Manage</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#rca_type" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'rca_type' ? 'active' : null }} ">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">RCA TYPE</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="rca_type" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/rca_type/create"
                            class="{{ Request::segment(1) === 'rca_type/create' ? 'active' : null }} loaderLoad">

                            <span class="">Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/rca_type"
                            class="{{ Request::segment(1) === 'rca_type' ? 'active' : null }} loaderLoad">

                            <span class="">Manage</span>
                        </a>
                    </li>
                </ul>
            </div>

            <li>
                <a data-bs-toggle="collapse" href="#rca" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'rca' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">RCA Mode</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="rca" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/rca/create"
                            class="{{ Request::segment(1) === 'rca/create' ? 'active' : null }} loaderLoad">

                            <span class="">Create RCA</span>
                        </a>
                    </li>
                    <li>
                        <a href="/rca" class="{{ Request::segment(1) === 'rca' ? 'active' : null }} loaderLoad">

                            <span class="">Manage RCA</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#rca2" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'rca' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">RCA2 Mode</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="rca2" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/rca2/create"
                            class="{{ Request::segment(1) === 'rca2/create' ? 'active' : null }} loaderLoad">

                            <span class="">Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/rca2" class="{{ Request::segment(1) === 'rca2' ? 'active' : null }} loaderLoad">

                            <span class="">Manage</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#Client" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'Client' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Client</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="Client" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/client"
                            class="{{ Request::segment(1) === 'client' ? 'active' : null }} loaderLoad">
                            <span class="">Manage</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#allocation" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'Client' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Allocation</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="allocation" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/allocation/qtl_qa"
                            class="{{ Request::segment(1) === 'allocation/qtl_qa' ? 'active' : null }} loaderLoad">
                            <span class="">QTL -> QA</span>
                        </a>
                    </li>
                    <li>
                        <a href="/allocation/qa_sheet"
                            class="{{ Request::segment(1) === 'allocation/qa_sheet' ? 'active' : null }} loaderLoad">
                            <span class="">QA -> QM-Sheet</span>
                        </a>
                    </li>
                    <li>
                        <a href="/allocation/qa_target"
                            class="{{ Request::segment(1) === 'allocation/qa_target' ? 'active' : null }} loaderLoad">
                            <span class="">Set QA Target</span>
                        </a>
                    </li>
                    <li>
                        <a href="/allocation/dump_uploader"
                            class="{{ Request::segment(1) === 'allocation/dump_uploader' ? 'active' : null }} loaderLoad">
                            <span class="">Agents / Call Dump Uploader</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#allocation1" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'Client' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Patner Month Target</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="allocation1" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/allocation/month_target_uploader"
                            class="{{ Request::segment(1) === 'allocation/month_target_uploader' ? 'active' : null }} loaderLoad">

                            <span class="">Upload Monthly Target</span>
                        </a>
                    </li>
                    <li>
                        <a href="/allocation/monthly_partner_targets"
                            class="{{ Request::segment(1) === 'allocation/monthly_partner_targets' ? 'active' : null }} loaderLoad">

                            <span class="">Monthly Target List</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a href="/allocation/raw_data_batch" class="d-flex"
                    class="{{ Request::segment(1) === 'allocation/raw_data_batch' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <span class="">Raw Data Batch</span>
                </a>
            </li>
            <li>
                <a href="/purge_data" class="d-flex"
                    class="{{ Request::segment(1) === 'purge_data' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <span class="">Purge Data</span>
                </a>
            </li>
         
            <li>
                <a data-bs-toggle="collapse" href="#qm_sheet" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'qm_sheet' ? 'active' : null }}">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">QM Sheet</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="qm_sheet" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/qm_sheet/create"
                            class="{{ Request::segment(1) === 'qm_sheet/create' ? 'active' : null }} loaderLoad">

                            <span class="">Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/qm_sheet"
                            class="{{ Request::segment(1) === 'qm_sheet' ? 'active' : null }} loaderLoad">

                            <span class="">QM Manage</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#cluster" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'cluster' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Cluster</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="cluster" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/cluster/create"
                            class="{{ Request::segment(1) === 'cluster/create' ? 'active' : null }} loaderLoad">

                            <span class="">Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/cluster"
                            class="{{ Request::segment(1) === 'cluster/create' ? 'active' : null }} loaderLoad">

                            <span class="">Manage</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a href="/raised_rebuttal_list" class="d-flex"
                    class="{{ Request::segment(1) === 'raised_rebuttal_list' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <span class="">My Rebuttal</span>
                </a>
            </li>
            <li>
                <a data-bs-toggle="collapse" href="#calibration" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'calibration' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Calibration</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="calibration" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/calibration/create"
                            class="{{ Request::segment(1) === 'calibration/create' ? 'active' : null }} loaderLoad">

                            <span class="">Create</span>
                        </a>
                    </li>
                    <li>
                        <a href="/calibration"
                            class="{{ Request::segment(1) === 'calibration' ? 'active' : null }} loaderLoad">

                            <span class="">Manage</span>
                        </a>
                    </li>
                    <li>
                        <a href="/calibration/my_request/list"
                            class="{{ Request::segment(1) === 'calibration/my_request/list' ? 'active' : null }} loaderLoad">

                            <span class="">Request</span>
                        </a>
                    </li>
                </ul>
            </div>
            <li>
                <a data-bs-toggle="collapse" href="#reports" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'report' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Reports</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>

            <div id="reports" class="collapse" data-bs-parent="#leftMenuUl">
                <ul>
                    <li>
                        <a href="/report/raw_dump_with_audit_report"
                            class="{{ Request::segment(1) === 'report/raw_dump_with_audit_report' ? 'active' : null }} loaderLoad">
                            <span class="">Raw Dump with Audit Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="/report/qc_report"
                            class="{{ Request::segment(1) === 'report/qc_report' ? 'active' : null }} loaderLoad">
                            <span class="">QC Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="/raised_rebuttal_list"
                            class="{{ Request::segment(1) === '/raised_rebuttal_list' ? 'active' : null }} loaderLoad">
                            <span class="">Raised Rebuttal List</span>
                        </a>
                    </li>
                    @if (Auth::user()->hasRole('process-owner'))
                    <li>
                        <a href="/report/rebuttal_status_report"
                            class="{{ Request::segment(1) === 'report/rebuttal_status_report' ? 'active' : null }} loaderLoad">
                            <span class="">Rebuttal Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="/report/rebuttal_summary"
                            class="{{ Request::segment(1) === 'report/rebuttal_summary' ? 'active' : null }} loaderLoad">

                            <span class="">Rebuttal Summary</span>
                        </a>
                    </li>
                    @endif
                   
                    @if (Auth::user()->hasRole('process-owner'))
                    <li>
                            <a href="/report/rebuttal_status_report"
                                class="{{ Request::segment(1) === 'report/rebuttal_status_report' ? 'active' : null }} loaderLoad">

                                <span class="d-block">Rebuttal Report</span>
                            </a>
                    </li>
                     @endif

                    <li>
                        <a href="/qa_target/qa_report"
                            class="{{ Request::segment(1) === 'qa_target/qa_report' ? 'active' : null }} loaderLoad">

                            <span class="d-block">QA Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="/qa_target/qa_performance"
                            class="{{ Request::segment(1) === 'qa_target/qa_performance' ? 'active' : null }} loaderLoad">

                            <span class="d-block">QA Performance</span>
                        </a>
                    </li>
                    <li>
                        <a href="/qa_target/qa_performance_matrix"
                            class="{{ Request::segment(1) === 'qa_target/qa_performance_matrix' ? 'active' : null }} loaderLoad">

                            <span class="d-block">QA Performance Matrix</span>
                        </a>
                    </li>
                    <li>
                        <a href="/report/new_agent_compliance"
                            class="{{ Request::segment(1) === 'report/new_agent_compliance' ? 'active' : null }} loaderLoad">

                            <span class="d-block">Agent Compliance</span>
                        </a>
                    </li>
                    <li>
                        <a href="/report/monthly_trending_report"
                            class="{{ Request::segment(1) === 'report/monthly_trending_report' ? 'active' : null }} loaderLoad">

                            <span class="d-block">Monthly Trending Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="/report/new_parameter_compliance"
                            class="{{ Request::segment(1) === 'report/new_parameter_compliance' ? 'active' : null }} loaderLoad">

                            <span class="d-block">Disposition & Parameter compliance</span>
                        </a>
                    </li>
                    <li>
                        <a href="/audit_estimation_report"
                            class="{{ Request::segment(1) === 'audit_estimation_report' ? 'active' : null }} loaderLoad">

                            <span class="d-block">Audit Estimation Report</span>
                        </a>
                    </li>
                </ul>
            </div>
            @elseif(Auth::user()->hasRole('client') && Auth::user()->id == 1817)
            <li>
                <a data-bs-toggle="collapse" href="#reports" data-bs-parent="#leftMenuUl" class="d-flex"
                    class="{{ Request::segment(1) === 'report' ? 'active' : null }} loaderLoad">
                    <img class="loaderLoad" src="/assets/design/img/rebuttal.svg" alt="dashboardicon" width="21"
                        height="20">
                    <!-- <div class="d-flex justify-content-between align-items-center w-100"> -->
                    <span class="">Reports</span>
                    <i class="bi bi-chevron-down arrow"></i>
                    <!-- </div> -->
                </a>
            </li>
            <div id="reports" class="collapse" data-bs-parent="#leftMenuUl">
            <ul>
                <li>
                    <a href="/report/new_parameter_compliance"
                        class="{{ Request::segment(1) === 'report/raw_dump_with_audit_report' ? 'active' : null }} loaderLoad">
                        <span class="">Disposition & Parameter compliance</span>
                    </a>
                </li>
                <li>
                    <a href="/report/raw_dump_with_audit_report"
                        class="{{ Request::segment(1) === 'report/raw_dump_with_audit_report' ? 'active' : null }} loaderLoad">
                        <span class="">Raw Dump with Audit Report</span>
                    </a>
                </li>
                <li>
                    <a href="/report/rebuttal_status_report"
                        class="{{ Request::segment(1) === 'report/raw_dump_with_audit_report' ? 'active' : null }} loaderLoad">
                        <span class="">Rebuttal Report</span>
                    </a>
                </li>
                <li>
                    <a href="/report/agent_performance_report"
                        class="{{ Request::segment(1) === 'report/raw_dump_with_audit_report' ? 'active' : null }} loaderLoad">
                        <span class="">Agent Performance Quartile Report</span>
                    </a>
                </li>
                <li>
                    <a href="/report/monthly_trending_report"
                        class="{{ Request::segment(1) === 'report/raw_dump_with_audit_report' ? 'active' : null }} loaderLoad">
                        <span class="">Monthly Trending Report</span>
                    </a>
                </li>
                <li>
                    <a href="/report/new_agent_compliance"
                        class="{{ Request::segment(1) === 'report/raw_dump_with_audit_report' ? 'active' : null }} loaderLoad">
                        <span class="">Agent Compliance</span>
                    </a>
                </li>
                <li>
                    <a href="/report/rebuttal_summary"
                        class="{{ Request::segment(1) === 'report/raw_dump_with_audit_report' ? 'active' : null }} loaderLoad">
                        <span class="">Rebuttal Summary</span>
                    </a>
                </li>
            </ul>
           </div>
            @endif












        <li>
            <a href="/profile" class="{{ Request::segment(1) === 'profile' ? 'active' : null }} loaderLoad">
                <img src="/assets/design/img/Profile.svg" class="loaderLoad" alt="dashboardicon" width="18"
                    height="20">
                <span class="d-block">Profile</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/logout') }}" class="loaderLoad"
                onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
                <img src="/assets/design/img/logout.svg" class="loaderLoad" alt="dashboardicon" width="18"
                    height="20">
                <span class="d-block">Logout</span>
            </a>
        </li>


    </ul>
    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>

</div>
<div class="loaderBox" id="loader_Box" style="display: none;">
    <lottie-player src="{{ asset('assets/design/img/loader.json') }}" background="transparent" speed="1"
        style="width: 200px;height: 200px;" loop autoplay></lottie-player>
</div>
<script type="text/javascript">
    function loader() {
        $('#loader_Box').show().css("display", "flex");
    }
    $(document).ready(function() {
        $(".loaderLoad").on("click", function() {
            loader();
        });
        $('#loader_Box').show().css("display", "none");
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
</script>
