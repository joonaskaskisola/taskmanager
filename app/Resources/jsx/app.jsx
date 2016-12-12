import React from 'react';
import { render } from 'react-dom';
import { Route, ReactRouter, Router, browserHistory } from 'react-router';
import CustomerApp from './customerApp.jsx';
import CategoryApp from './categoryApp.jsx';
import CountryApp from './countryApp.jsx';
import InboxApp from './inboxApp.jsx';
import ItemApp from './itemApp.jsx';
import TaskApp from './taskApp.jsx';
import UnitApp from './unitApp.jsx';
import ProfileApp from './profileApp.jsx';
import MenuApp from './menu.jsx';

render(
    <Router history={browserHistory}>
        <Route path='/' component={MenuApp}>
            <Route path='customers' component={CustomerApp}/>
            <Route path='category' component={CategoryApp}/>
            <Route path='country' component={CountryApp}/>
            <Route path='inbox' component={InboxApp}/>
            <Route path='items' component={ItemApp}/>
            <Route path='tasks' component={TaskApp}/>
            <Route path='unit' component={UnitApp}/>
            <Route path='profile' component={ProfileApp}/>
        </Route>
    </Router>,
    document.getElementById('app')
);
