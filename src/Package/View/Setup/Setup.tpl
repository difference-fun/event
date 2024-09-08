{{R3M}}
{{$register = Package.Difference.Fun.Event:Init:register()}}
{{if(!is.empty($register))}}
{{Package.Difference.Fun.Event:Import:role.system()}}
{{Package.Difference.Fun.Event:Import:event.action()}}
{{Package.Difference.Fun.Event:Import:event()}}
Import System.Event
{{/if}}