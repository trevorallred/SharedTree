var searchwindow = '';

function focus_searchwindow( sql_search ) {
    if ( searchwindow && !searchwindow.closed && searchwindow.location) {
        var searchwindow = searchwindow;
        searchwindow.focus();
    } else {
        url = 'person_choose.php?' + sql_search;
        open_searchwindow( url );
    }
}

function open_searchwindow( url ) {
    if ( ! url ) {
        url = 'person_choose.php';
    }

    if (!searchwindow.closed && searchwindow.location) {
        goTo( url, 'search' );
        searchwindow.focus();
    } else {
        searchwindow=window.open( url, '',
            'toolbar=0,location=0,directories=0,status=1,menubar=0,' +
            'scrollbars=yes,resizable=yes,' +
            'width=400,height=500');
    }

    if ( ! searchwindow.opener ) {
       searchwindow.opener = window.window;
    }

    if ( window.focus ) {
        searchwindow.focus();
    }

    return true;
}

/**
 * opens new url in target frame, with default beeing left frame
 * valid is 'main' and 'searchwindow' all others leads to 'left'
 *
 * @param    string    targeturl    new url to load
 * @param    string    target       frame where to load the new url
 */
function goTo( targeturl, target ) {
    //alert('goto' + targeturl + target);
    if ( target == 'main' ) {
        target = window.frames[1];
    } else if ( target == 'search' ) {
        target = searchwindow;
        //return open_searchwindow( targeturl );
    } else if ( ! target ) {
        target = window.frames[0];
    }

    return true;
}
