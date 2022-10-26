// Angular
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes = [
	{path: 'auth', loadChildren: 'app/views/pages/auth/auth.module#AuthModule'},

	// enable this router to set which demo theme to load,
	{path: '', loadChildren: 'app/views/themes/demo1/theme.module#ThemeModule'},

	{path: '**', redirectTo: 'error/403', pathMatch: 'full'},
];

@NgModule({
	imports: [
		RouterModule.forRoot(routes)
	],
	exports: [RouterModule]
})
export class AppRoutingModule {
}
