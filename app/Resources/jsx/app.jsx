import React from 'react';
import {render} from 'react-dom';
import {IndexRoute, Route, ReactRouter, Router, hashHistory} from 'react-router';
import DefaultApp from './defaultApp.jsx';
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
	<Router history={hashHistory}>
		<Route path='/' component={MenuApp}>
			<IndexRoute component={DefaultApp}/>

			<Route path='customer'>
				<IndexRoute showEditor={false} component={CustomerApp}/>
				<Route path='new' showEditor={true} component={CustomerApp}/>
				<Route path=':id' showEditor={true} component={CustomerApp}/>
			</Route>

			<Route path='category'>
				<IndexRoute showEditor={false} component={CategoryApp}/>
				<Route path='new' showEditor={true} component={CategoryApp}/>
				<Route path=':id' showEditor={true} component={CategoryApp}/>
			</Route>

			<Route path='country'>
				<IndexRoute showEditor={false} component={CountryApp}/>
				<Route path='new' showEditor={true} component={CountryApp}/>
				<Route path=':id' showEditor={true} component={CountryApp}/>
			</Route>

			<Route path='item'>
				<IndexRoute showEditor={false} component={ItemApp}/>
				<Route path='new' showEditor={true} component={ItemApp}/>
				<Route path=':id' showEditor={true} component={ItemApp}/>
			</Route>

			<Route path='task'>
				<IndexRoute showEditor={false} component={TaskApp}/>
				<Route path='new' showEditor={true} component={TaskApp}/>
				<Route path=':id' showEditor={true} component={TaskApp}/>
			</Route>

			<Route path='unit'>
				<IndexRoute showEditor={false} component={UnitApp}/>
				<Route path='new' showEditor={true} component={UnitApp}/>
				<Route path=':id' showEditor={true} component={UnitApp}/>
			</Route>

			<Route path='inbox'>
				<IndexRoute showEditor={false} component={InboxApp}/>
				<Route path='new' showEditor={true} component={InboxApp}/>
				<Route path=':id' showEditor={true} component={InboxApp}/>
			</Route>

			<Route path='profile' component={ProfileApp}/>
		</Route>
	</Router>,
	document.getElementById('app')
);
