@extends('admin.newlayout.layout')
@section('title')
    Purchase Plugin
@endsection
@section('page')
    <section class="card">
        <div class="card-body text-center">
            <div class="h-10"></div>
            <img src="/assets/admin/img/quiz_thumb.png" style="display:inline-block;margin: 0 auto 0;">
            <div class="h-10"></div>
            <p class="text-left">
                <b>Quiz plugin features:</b><br>
                Unlimited quizzes: Instructors can create unlimited quizzes and assign them to their courses.<br>
                Unlimited questions: Every quiz can include unlimited questions.<br>
                Various questions: This plugin supports multiple-choice, descriptive, and image questions. Instructors can specify the grade for each question.<br>
                Pass mark: The instructor can specify the pass mark for each quiz.<br>
                Quiz attempts: The instructor can set fail attempts for each quiz.<br>
                Quiz time: Quizzes can be defined for unlimited time or limited time. If the instructor set time, the quiz will be finished after the countdown stopped.<br>
                Auto & manual correction: The result for the multiple-choice questions will be calculated automatically. If a quiz includes one or more descriptive questions, the result should be manually for this kind of question.<br>
                Results: The instructor and admin can manage the results and analyze them with expanded details.<br>
                <div class="h-10" style="height: 10px;"></div>
                <b>Certificates plugin features:</b><br>
                Certificates: Instructors can enable certificate generation for each quiz.<br>
                Conditional quizzes: Instructors can enable conditions for certificates. If a student passes a quiz successfully, the certificate will be generated.<br>
                Certificate customization: Admin can customize the certificate design. The background and text can be changed from the admin panel.<br>
            </p>
            <div class="h-10" style="height: 10px;"></div>
            <div class="h-10"></div>
            <h2><a class="btn btn-primary" href="https://codecanyon.net/user/prodevelopers_team/portfolio" target="_blank">Purchase</a></h2>
        </div>
    </section>
@stop
