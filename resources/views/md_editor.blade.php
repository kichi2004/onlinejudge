@extends('layouts.base')
@section('title',__('name.md_editor'))
@section('main')
<main class="h-100 my-4 py-4 bg-white shadow-sm rounded d-flex">
    <div class="col m-3 p-0 border">
        <textarea class="w-100 h-100 p-0 m-0" id="editor"></textarea>
    </div>
    <div class="col m-3 border" id="output">
    </div>
</main>
@endsection

@section('style')
<style>
#editor{
    resize: none;
}
html,body,main{
    height:100%;
}
</style>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/0.5.1/marked.min.js"></script>
<script src="{{asset('katex/katex.min.js')}}"></script>
<script src="{{asset('katex/contrib/auto-render.min.js')}}"></script>
<script src="{{asset('js/mdparse.js')}}"></script>
<script>
$(function(){
    $('#editor').on('keyup',function(){
        renderMD($('#editor').val(),$('#output'));
        //renderMD($('#editor').html(),$('#output'),false);
    })
})
</script>
@endsection